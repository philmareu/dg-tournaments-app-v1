<?php

namespace DGTournaments\Services\Pdga\Helpers;


use DGTournaments\Services\API\Payloads\CourseDataPayload;
use DGTournaments\Services\API\Responses\CoursesResponse;
use DGTournaments\Services\Pdga\EndPoints\Course;
use Illuminate\Support\Collection;

class Courses
{
    private $countries = [
        'AD',
        'AE',
        'AF',
        'AG',
        'AI',
        'AL',
        'AM',
        'AO',
        'AQ',
        'AR',
        'AS',
        'AT',
        'AU',
        'AW',
        'AX',
        'AZ',
        'BA',
        'BB',
        'BD',
        'BE',
        'BF',
        'BG',
        'BH',
        'BI',
        'BJ',
        'BL',
        'BM',
        'BN',
        'BO',
        'BQ',
        'BR',
        'BS',
        'BT',
        'BV',
        'BW',
        'BY',
        'BZ',
        'CA',
        'CC',
        'CD',
        'CF',
        'CG',
        'CH',
        'CI',
        'CK',
        'CL',
        'CM',
        'CN',
        'CO',
        'CR',
        'CU',
        'CV',
        'CW',
        'CX',
        'CY',
        'CZ',
        'DE',
        'DJ',
        'DK',
        'DM',
        'DO',
        'DZ',
        'EC',
        'EE',
        'EG',
        'EH',
        'ER',
        'ES',
        'ET',
        'FI',
        'FJ',
        'FK',
        'FM',
        'FO',
        'FR',
        'GA',
        'GB',
        'GD',
        'GE',
        'GF',
        'GG',
        'GH',
        'GI',
        'GL',
        'GM',
        'GN',
        'GP',
        'GQ',
        'GR',
        'GS',
        'GT',
        'GU',
        'GW',
        'GY',
        'HK',
        'HM',
        'HN',
        'HR',
        'HT',
        'HU',
        'ID',
        'IE',
        'IL',
        'IM',
        'IN',
        'IO',
        'IQ',
        'IR',
        'IS',
        'IT',
        'JE',
        'JM',
        'JO',
        'JP',
        'KE',
        'KG',
        'KH',
        'KI',
        'KM',
        'KN',
        'KP',
        'KR',
        'KW',
        'KY',
        'KZ',
        'LA',
        'LB',
        'LC',
        'LI',
        'LK',
        'LR',
        'LS',
        'LT',
        'LU',
        'LV',
        'LY',
        'MA',
        'MC',
        'MD',
        'ME',
        'MF',
        'MG',
        'MH',
        'MK',
        'ML',
        'MM',
        'MN',
        'MO',
        'MP',
        'MQ',
        'MR',
        'MS',
        'MT',
        'MU',
        'MV',
        'MW',
        'MX',
        'MY',
        'MZ',
        'NA',
        'NC',
        'NE',
        'NF',
        'NG',
        'NI',
        'NL',
        'NO',
        'NP',
        'NR',
        'NU',
        'NZ',
        'OM',
        'PA',
        'PE',
        'PF',
        'PG',
        'PH',
        'PK',
        'PL',
        'PM',
        'PN',
        'PR',
        'PS',
        'PT',
        'PW',
        'PY',
        'QA',
        'RE',
        'RO',
        'RS',
        'RU',
        'RW',
        'SA',
        'SB',
        'SC',
        'SD',
        'SE',
        'SG',
        'SH',
        'SI',
        'SJ',
        'SK',
        'SL',
        'SM',
        'SN',
        'SO',
        'SR',
        'SS',
        'ST',
        'SV',
        'SX',
        'SY',
        'SZ',
        'TC',
        'TD',
        'TF',
        'TG',
        'TH',
        'TJ',
        'TK',
        'TL',
        'TM',
        'TN',
        'TO',
        'TR',
        'TT',
        'TV',
        'TW',
        'TZ',
        'UA',
        'UG',
        'UM',
        'US',
        'UY',
        'UZ',
        'VA',
        'VC',
        'VE',
        'VG',
        'VI',
        'VN',
        'VU',
        'WF',
        'WS',
        'YE',
        'YT',
        'ZA',
        'ZM',
        'ZW',
    ];

    protected $batch = [];

    public function getCourses()
    {
        dump(collect($this->countries)->first());
        $this->buildCourseBatches(collect($this->countries));

        $courses = collect($this->batch)->filter(function($course) {
            return isset($course['latitude']) && isset($course['longitude']);
        })->reject(function($course) {
            return is_null($course['latitude']) || is_null($course['longitude']) || $course['latitude'] == "" || $course['longitude'] == "";
        })->map(function($course) {

            $payload = new CourseDataPayload([
                'id' => $course['course_id'],
                'name' => $course['course_name'],
                'address' => $course['street'],
                'address_2' => $course['street2'],
                'city' => $course['city'],
                'state_province' => $course['state_province'],
                'country' => $course['country'],
                'description' => $course['course_description'],
                'directions' => isset($course['directions']) ? $course['directions'] : null,
                'length' => isset($course['total_length_of_course']) ? $course['total_length_of_course'] : null,
                'latitude' => $course['latitude'],
                'longitude' => $course['longitude']
            ]);

            $payload->verifyPayload();

            return $payload;
        });

        return new CoursesResponse(200, $courses);
    }

    protected function buildCourseBatches(Collection $countries, $offset = 0)
    {
        dump('Batch: ' . $offset);
        $api = new Course();
        $courses = $api->whereCountry($countries->first())->limit(200)->offset($offset)->get();

        if($countries->count() > 1)
        {
            if(count($courses))
            {
                $this->batch = array_merge($this->batch, $courses);
                $this->buildCourseBatches($countries, $offset + 200);
            }
            else
            {
                $countries->shift();
                dump($countries->first());
                $this->buildCourseBatches($countries);
            }
        }
    }
}