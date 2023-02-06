<?php

namespace App\Models\Shopify;

use App\Models\Image;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;


/**
 * Class JudgeMe
 *
 * @package App\Models\Shopify
 * @property int $id
 * @property string $review_id
 * @property string $reviewer_id
 * @property string $reviewer_name
 * @property string $reviewer_email
 * @property int $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe query()
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereReviewerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereReviewerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JudgeMe whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class JudgeMe extends Model
{
    protected $guarded = ['id'];

    public function images()
    {
        return $this->morphMany(Image::class, 'imagable');
    }

    public static function getReviews($page = 1, $rating = 5) {
        $client = new Client([
            'base_uri' => 'https://judge.me/api/v1/'
        ]);
        $response = $client->request('GET', "reviews?api_token=wtYRd0jxmmOemEjSQA29vLcbIwM&shop_domain=skar-audio.myshopify.com&perPage=100&page=$page&rating=$rating");
        try {
            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $e;
        }
    }
}
