<?php
namespace Mio\TestUtils\Helper;


class SiteInfoHelper
{

    public static function getRandomSalutations($number){
        $salutations = array(
            'Mr.' => 'Mr.',
            'Mrs.' => 'Mrs.',
            'Ms.' => 'Ms.',
        );

        return array_rand($salutations, $number);
    }

    public static function getRandomVietNamCities($number){
        $cities = array(
            '452' => 'Ha Noi',
            '453' => 'Ho Chi Minh',
            '455' => 'Da Nang',
        );

        return array_rand($cities, $number);
    }


    public static function getRandomJobLevels($number){
        $job_level = array(
            262 => 'Student/Intern',
            263 => 'Entry Level',
            264 => 'Experienced (Non-manager)',
            265 => 'Team Leader/Supervisor',
            266 => 'Manager',
            267 => 'Director',
            268 => 'Vice-President/Senior Vice-President',
            269 => 'C-level (CEO, CFO, CTO, President, etc.)'
        );

        return array_rand($job_level, $number);
    }

    public static function getRandomJobFunctions($number){
        $job_functions = array(
            67 => 'Accounting/Finance',
            2195 => 'Marketing',
            64 => 'Human Resources',
            61 => 'Legal',
            2193 => 'Management',
            62 => 'Market Research',
            2179 => 'Customer Service',
            66 => 'IT/Technical',
            2170 => 'Admin/Clerical/Translator',

        );

        return array_rand($job_functions, $number);
    }

   public static function getRandomIndustries($number){
       $my_expertises = array(
           2171  => 'Advertising/PR',
           2174  => 'Arts/Design',
           49    => 'Entertainment',
           2169  =>  'Accounting/Auditing',
           2184    => 'Food/Beverage/Diary',
           53    => 'Restaurant/Catering services',
           2191    => 'IT - Software',
           2197    => 'Oil/Gas/Mineral',
           2188    => 'Headhunting & HR Services',
           16791 => 'Internet/Online Media',
           2205  => 'Telecommunications',
       );

       return array_rand($my_expertises, $number);
   }
}
