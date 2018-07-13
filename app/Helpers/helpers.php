<?php

function pr($in)
{
    echo "<pre>";
    print_r($in);
    echo "</pre>";
    exit;
}
function accountKey($length)
{
    $characters = '0123456748894578435743152778467723578981329tweh934thwg98g3utwe79ur3qeg9bqe98sdhq86wegsiu6g83wbesrd6g8yb2t4wgrhi9g4fyqe8hoit42gwe8UVH8V2BH8VB18239VU2BV9B7C21E8H39VBC8129Q8C9J1340J9N50MJN98B7V86CF75F6V8B39NIJMTYTHEVG8F5R82379GW478EHUTI9B8FW59T68G7EHYDTNIFJPINB96T39WEBUTRNIYV62Q5B3WFEGTJ8Y0U7JHR0EGRFY89abcdefghijklmnopqrstut87guiq20rwoheis8y3tbgry94erhod6t82qrgesg8ey0thj560tyjin7gv92qwus5q72fwsd5qwtfd5qf2wtedg95yrhtf6580thgi25r73wegry0u5trho2z3xw4c5rv6ynu7n6btrguefyjvweBAF72RGETDRFJGYIKU0V82WUBHCH903WHVBUCNB92VNC98B02VNgbrhtf945ypethrgu27q6wregrudvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters .= 'gv12h89egwre9vvcbu23b49uvi39qv8buci01v2vg79bunci01b29qvewgni40bv79gcufh29gv6f8cvy1b249weuqd9vb79b3n80h3v7g92cu9hgv72un0c8h92v3b4bn9vucni02v3bh8vn0iq8hv79b3niv8h7qgbyu2v4b7g2cbun0wb9ybuvn03972cb1un0v2n3b7G23WH849UBN3VHW9q9h8h023vwb08hvni308hbni';
    $characters .= '68FYVBUNId6c7vyb9079fd57tfyibuon354D657TFYHUOB9GVYBNIP0H7G9YB6FD54RC7VYG6F8VYBU7575f6fvyibh807g9buh809g7ybiuo98tcd46sdrds35ercg79boniphubh9g7y8t76s5w3aezxrcf757t897gy';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function renderStar($score, $times)
{
    if ($score != 0 && $times != 0) {
        $average = $score / $times;
        $split = explode('.', $average);
        $star = [];

        if ($split[0]) {
            for ($i = 0; $i < $split[0]; $i++) {
                $star[] = 'star';
            }

            if (isset($split[1])) {
                if ($split[1] <= 5) {
                    array_push($star, 'star-half-o');
                } else {
                    array_push($star, 'star');
                }
            }
        }
        $n = 5 - count($star);
        for ($i = 0; $i < $n; $i++) {
            array_push($star, 'star-o');
        }
    } else {
        $star = [
            'star-o',
            'star-o',
            'star-o',
            'star-o',
            'star-o',
        ];
    }

    return $star;
}

function checkCharacter($word)
{
    $matched = preg_match('/[^A-Za-z0-9 ]+/', $word) ? false : true;

    return $matched;
}
