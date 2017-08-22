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
        $taggedUrl = [];

        $labelsDir = dirname(__FILE__).'/labels/';
        if (file_exists($labelsDir)) {
            $labels = array_diff(scandir ($labelsDir), array('..', '.'));
            // dd($labels);
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
        }

        $tags = json_decode(File::get(dirname(__FILE__).'/json/whitelist_tags.json'));
        foreach ($tags as $tag) {
            Tag::create($this->object_to_array($tag));
        }

        $honConducts = json_decode(File::get(dirname(__FILE__).'/json/honconducts.json'));

        foreach ($honConducts as $honConduct) {

            $service = Service::create([
                'name' => html_entity_decode($honConduct->name),
                'description' => html_entity_decode($honConduct->description),
            ]);

            $langs = explode(',', $honConduct->language);
            foreach ($langs as $lang) {
                if (!empty($lang)) {
                    $langTag = Tag::firstOrCreate(array('name' => trim($lang), 'type' => 'language'));
                    $langTag->services()->attach($service->id);
                }
            }

            if ($honConduct->certified) {
                $certifiedTag = Tag::where('name', 'certified')->firstOrFail();
                $certifiedTag->services()->sync([$service->id], false);
            }

            // If keyword exist, attached to service. (prevent useless keywords)
            foreach ($honConduct->keywords as $keyword) {
                $tag = Tag::where('name', $keyword)->first();
                if ($tag) {
                    $tag->services()->sync([$service->id], false);
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
                $service->tags()->sync($taggedUrl[$app->url], false);
            }
        }

        $questions = json_decode(File::get(dirname(__FILE__).'/json/questions.json'));
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

        $applications = json_decode(File::get(dirname(__FILE__).'/json/applications.json'));

        $androidPlatform = Platform::where('name', 'android')->firstOrFail();
        $iosPlatform = Platform::where('name', 'ios')->firstOrFail();

        foreach ($applications as $application) {
            $service = Service::where('name', $application->title)->first();
            if (empty($service)) {
                $service = Service::Create([
                    'name' => html_entity_decode ( $application->title),
                    'description' => html_entity_decode ( $application->description),
                ]);
            }


            $langs = explode(',', trim($application->language, ","));
            foreach ($langs as $lang) {
                if (!empty($lang)) {
                    $langTag = Tag::firstOrCreate(array('name' => trim($lang), 'type' => 'language'));
                    $langTag->services()->sync([$service->id], false);
                }
            }

            if (property_exists($application, 'certified')) {
                if($application->certified) {
                    $certifiedTag = Tag::where('name', 'certified')->firstOrFail();
                    $certifiedTag->services()->sync([$service->id], false);
                }
            }

            // If keyword exist, attached to service. (prevent useless keywords)
            if (isset($application->keywords)) {
                foreach ($application->keywords as $keyword) {
                    $tag = Tag::where('name', $keyword)->first();
                    if ($tag) {
                        $tag->services()->sync([$service->id], false);
                    }
                }
            }

            if ($application->android) {
                $application->android = trim($application->android, "/");
                $androidApp = App::create([
                    'url' => $application->android,
                    'serv_id' => $service->id,
                    'plat_id' => $androidPlatform->id
                ]);
            }

            if ($application->ios) {
                $application->ios = trim($application->ios, "/");
                $iosApp = App::create([
                    'url' => $application->ios,
                    'serv_id' => $service->id,
                    'plat_id' => $iosPlatform->id
                ]);
            }
        }
    }
}
