<?php namespace HON\HonCuratorReview\Updates;

use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Question;
use HON\HonCuratorReview\Models\Responsetype;
use HON\HonCuratorReview\Models\Service;
use HON\HonCuratorReview\Models\Tag;
use HON\HonCuratorUser\Models\Activity;
use Illuminate\Support\Facades\File;
use October\Rain\Database\Updates\Seeder;

class SeedFromJSON extends Seeder
{
    private function object_to_array($object)
    {
        return (array) $object;
    }

    public function run()
    {
        $labelsDir = './plugins/hon/honcuratorreview/updates/labels/';
        $labels = array_diff(scandir ($labelsDir), array('..', '.'));
        // dd($labels);
        $taggedUrl = [];
        foreach ($labels as $label) {
            $labelName = substr($label, 10, -4);
            if (substr($labelName , 0, 2)== 'SL') {
                $tag = Tag::firstOrCreate(array('name' => $labelName, 'type' => 'financial'));
            } else {
                $tag = Tag::firstOrCreate(array('name' => $labelName, 'type' => 'theme'));
            }

            $lines = file($labelsDir.$label);
            foreach ($lines as $line) {
                $line = trim($line);
                $line = trim($line, "/");
                if(isset($taggedUrl[trim($line)])){
                    $taggedUrl[$line][] = $tag->id;
                } else {
                    $taggedUrl[$line] = [$tag->id];
                }
            }
        }

        $tags = json_decode(File::get('./plugins/hon/honcuratorreview/updates/json/hon_tags.json'));
        foreach ($tags as $tag) {
            Tag::create($this->object_to_array($tag));
        }

        $honConducts = json_decode(File::get('./plugins/hon/honcuratorreview/updates/json/honconducts.json'));

        foreach ($honConducts as $honConduct) {
            $langs = explode(',', $honConduct->language);
            foreach ($langs as $lang) {
                Tag::firstOrCreate(array('name' => trim($lang), 'type' => 'language'));
            }
            $service = Service::create([
                'name' => $honConduct->name,
                'description' => $honConduct->description,
            ]);

            if ($honConduct->certified) {
                $certifiedTag = Tag::where('name', 'certified')->firstOrFail();
                $certifiedTag->services()->attach($service->id);
            }

            // If keyword exist, attached to service. (prevent useless keywords)
            foreach ($honConduct->keywords as $keyword) {
                $tag = Tag::where('name', $keyword)->first();
                if ($tag) {
                    $tag->services()->attach($service->id);
                }
            }

            $platform = Platform::where('name', $honConduct->platform)->firstOrFail();
            $honConduct->url = trim($honConduct->url, "/");
            $app = App::create([
                'url' => $honConduct->url,
                'serv_id' => $service->id,
                'plat_id' => $platform->id
            ]);

            // then check if the url has hon labels
            if (isset($taggedUrl[$app->url])) {
                foreach ($taggedUrl[$app->url] as $honTags) {
                    $service->tags()->attach($honTags);
                }
            }
        }

        $questions = json_decode(File::get('./plugins/hon/honcuratorreview/updates/json/questions.json'));
        foreach ($questions as $quest) {
            $responseType = Responsetype::where('label', $quest->responsetype)->firstOrFail();
            $question = Question::create([
               "question" => $quest->question,
                "responsetype_id" => $responseType->id
            ]);
            $activities = array();
            foreach ($quest->activities as $activity) {
                $oActivity = Activity::where('label', $activity)->first();
                if ($oActivity) {
                    $activities[] = $oActivity->id;
                }
            }
            $platforms = array();
            foreach ($quest->platforms as $platform) {
                $oPlatform = Platform::where('name', $platform)->first();
                if ($oPlatform) {
                    $platforms[] = $oPlatform->id;
                }
            }

            $question->activities()->attach($activities);
            $question->platforms()->attach($platforms);
        }

    }
}
