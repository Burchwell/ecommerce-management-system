<?php

namespace App\Console\Commands\Shopify;

use App\Models\Image;
use App\Models\Shopify\JudgeMe;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class JudgeMeImportLatestReviews
 * @package App\Console\Commands\Shopify
 */

class JudgeMeImportLatestReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopify:import:reviews {--all=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Latest Judgeme Reviews';

    private $page = 1;
    private $rating = 5;
    private $last_review_id = null;
    private $stop = false;


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all = $this->option('all');

        if ($all === false) {
            $this->last_review_id = optional(JudgeMe::orderBy('created_at', 'DESC')
                ->first())->review_id;
        }

        while ($reviews = JudgeMe::getReviews($this->page, $this->rating)) {
            if (empty($reviews['reviews'])) {
                break;
            }

            if ($all === false && $this->stop === true) {
                break;
            }

            $this->_saveReviews($reviews['reviews']);
            $this->page++;
        }
    }

    private function _saveReviews($reviews) {
        $output = new ConsoleOutput();

        foreach ($reviews as $review) {
            if ($this->last_review_id !== null && ((int) $review['id'] === (int) $this->last_review_id)) {
                $output->writeln("All new reviews imported");
                $this->stop = true;
            } else if (!empty($review['pictures'])) {
                $output->writeln("Processing Review # {$review['id']}. Stopping at {$this->last_review_id}.");
                $newReview = JudgeMe::updateOrCreate(
                    ['review_id' => $review['id'], 'reviewer_id' => $review['reviewer']['id'], 'reviewer_email' => $review['reviewer']['email'], 'reviewer_name' => $review['reviewer']['name'], 'rating' => $review['rating'], 'created_at' => $review['created_at']],
                    ['review_id' => $review['id'], 'reviewer_id' => $review['reviewer']['id'], 'reviewer_email' => $review['reviewer']['email'], 'reviewer_name' => $review['reviewer']['name'], 'rating' => $review['rating'], 'created_at' => $review['created_at']]
                );

                $this->_storeImages($review['pictures'], $newReview);
            } else {
                $output->writeln("Review # {$review['id']} doesn't include images.");
            }
        }
    }

    private function _storeImages($images, $review) {
        $s3 = Storage::disk('s3');
        foreach ($images as $image) {
            $name = explode('?', substr($image['urls']['original'], strrpos($image['urls']['original'], '/') + 1))[0];
            $path = "/images/user-photo-gallery/$name";
            try {
                $s3->put($path, file_get_contents($image['urls']['huge']), 'public');
                $dbvalues = ['url' => $s3->url($path), 'filename' => $name, 'path' => $path, 'imagable_id' => $review->getKey(), 'imagable_type' => JudgeMe::class];
                $image = Image::firstOrNew($dbvalues);
                $image->save();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
