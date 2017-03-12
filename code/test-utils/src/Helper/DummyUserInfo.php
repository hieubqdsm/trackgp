<?php
namespace Mio\TestUtils\Helper;

use Mio\ViFaker\ViFaker;


class DummyUserInfo
{
    /** @var  string */
    protected $email;
    /** @var  string */
    protected $password;
    /** @var  string */
    protected $salutation;
    /** @var  string */
    protected $firstname;
    /** @var  string */
    protected $lastname;
    /** @var  int */
    protected $birthday;
    /** @var  int */
    protected $birthmonth;
    /** @var  int */
    protected $birthyear;
    /** @var  int */
    protected $country_value_int;
    /** @var  int */
    protected $city_value_int;
    /** @var  string */
    protected $phone;
    /** @var  int */
    protected $job_function_value_int;
    /** @var  array */
    protected $industry_value_ints;
    /** @var  string */
    protected $work_at_company_name;
    /** @var  int */
    protected $job_level;
    /** @var  string */
    protected $job_title;
    /** @var  int */
    protected $period_from_month;
    /** @var  int */
    protected $period_from_year;
    /** @var  int */
    protected $period_to_month;
    /** @var  int */
    protected $period_to_year;

    public function __construct(
        $email,
        $password,
        $salutation,
        $firstname,
        $lastname,
        $birthday,
        $birthmonth,
        $birthyear,
        $country_value_int,
        $city_value_int,
        $phone,
        $job_function_value_int,
        $industry_value_ints,
        $work_at_company_name,
        $job_level,
        $job_title,
        $period_from_month,
        $period_from_year,
        $period_to_month,
        $period_to_year
    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->salutation = $salutation;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthday = (int)$birthday;
        $this->birthmonth = (int)$birthmonth;
        $this->birthyear = $birthyear;
        $this->country_value_int = $country_value_int;
        $this->city_value_int = $city_value_int;
        $this->phone = $phone;
        $this->job_function_value_int = $job_function_value_int;
        $this->industry_value_ints = $industry_value_ints;
        $this->work_at_company_name = $work_at_company_name;
        $this->job_level = $job_level;
        $this->job_title = $job_title;
        $this->period_from_month = $period_from_month;
        $this->period_from_year = $period_from_year;
        $this->period_to_month = $period_to_month;
        $this->period_to_year = $period_to_year;
    }

    public static function generateDummyUserInfo()
    {
        $faker = ViFaker::create();
        $email = $faker->email;
        $period_from_year = $faker->year('2005-10-21T16:05:52+0000');

        return new self(
            $email,
            $password = $email,
            $salutation = SiteInfoHelper::getRandomSalutations(1),
            $firstname = $faker->firstname,
            $lastname = $faker->lastName,
            $birthday = (int)$faker->dayOfMonth,
            $birthmonth = (int)$faker->month,
            $birthyear = $faker->year("1993-10-21T16:05:52+0000"),
            $live_in_country = 331,
            $city = SiteInfoHelper::getRandomVietNamCities(1),
            $phone = $faker->phoneNumber,
            $job_function = SiteInfoHelper::getRandomJobFunctions(1),
            $expertises = SiteInfoHelper::getRandomIndustries(1),
            $work_at_company_name = $faker->company,
            $job_level = SiteInfoHelper::getRandomJobLevels(1),
            $job_title = $faker->text(50),
            $period_from_month = (int)$faker->month,
            $period_from_year,
            $period_to_month = (int)$faker->month,
            $period_to_year = $period_from_year + 1
        );


    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return int
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @return int
     */
    public function getBirthmonth()
    {
        return $this->birthmonth;
    }

    /**
     * @return int
     */
    public function getBirthyear()
    {
        return $this->birthyear;
    }

    /**
     * @return int
     */
    public function getCountryValueInt()
    {
        return $this->country_value_int;
    }

    /**
     * @return int
     */
    public function getCityValueInt()
    {
        return $this->city_value_int;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * @return int
     */
    public function getJobFunctionValueInt()
    {
        return $this->job_function_value_int;
    }

    /**
     * @return array
     */
    public function getIndustryValueInts()
    {
        return $this->industry_value_ints;
    }

    /**
     * @return string
     */
    public function getWorkAtCompanyName()
    {
        return $this->work_at_company_name;
    }

    /**
     * @return int
     */
    public function getJobLevel()
    {
        return $this->job_level;
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return $this->job_title;
    }

    /**
     * @return int
     */
    public function getPeriodFromMonth()
    {
        return $this->period_from_month;
    }

    /**
     * @return int
     */
    public function getPeriodFromYear()
    {
        return $this->period_from_year;
    }

    /**
     * @return int
     */
    public function getPeriodToMonth()
    {
        return $this->period_to_month;
    }

    /**
     * @return int
     */
    public function getPeriodToYear()
    {
        return $this->period_to_year;
    }

}
