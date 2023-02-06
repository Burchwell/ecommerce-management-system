<?php

namespace App\Models\Shopify;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shopify\OrderTransaction
 *
 * @property int $id
 * @property int $order_id
 * @property int $transaction_id
 * @property int $parent_id
 * @property string $amount
 * @property string $authorization
 * @property string $currency
 * @property string|null $error_code
 * @property string $gateway
 * @property string $kind
 * @property string $message
 * @property string $payment_details
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $processed_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereAuthorization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction wherePaymentDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderTransaction extends Model
{
    //
}
