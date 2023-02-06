<?php
if (preg_match($r, $n, $tracking_number) != 0) {
    switch (substr($tracking_number[0],, 0,2)) {
        case '94':
            $carrier_url = printf( "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=%s", $tracking_number[0]);
            break;
        case '27':
            $carrier_url = printf(  "https://www.fedex.com/fedextrack/?trknbr=%s", $tracking_number[0]);
            break;
        default:
            $carrier_url = printf(  "https://www.ups.com/track?loc=en_US&tracknum=%s", $tracking_number[0]);
            break;
    }
}
