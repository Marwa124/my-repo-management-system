<?php

use App\Models\Role;
use Illuminate\Database\Seeder;
use Modules\HR\Entities\AccountDetail;

class AccountDetailsTableSeeder extends Seeder
{
    public function run()
    {
        $accountDetails = [
            [
                'id'    => '2',
                'fullname' => 'Mohab Mostafa Sayed',
                'phone' => '201006057763',
                'mobile' => '201006057763',
                'joining_date' => '2019-01-01',
                'date_of_birth' => '1986-02-23',
                'user_id' => '2',
                'designation_id' => '1',
                'employment_id' => '3',
            ],
            [
                'id'    => '3',
                'fullname' => 'Mohamed Ayman Mohamed',
                'phone' => '201006057763',
                'mobile' => '201006057763',
                'joining_date' => '2019-07-01',
                'date_of_birth' => '1986-02-23',
                'user_id' => '3',
                'designation_id' => '15',
                'employment_id' => '6',
            ],
            [
                'id'    => '4',
                'fullname' => 'Ahmed Abo-Zeid',
                'phone' => '201550131255',
                'mobile' => '201550131255',
                'joining_date' => '2019-07-01',
                'date_of_birth' => '1986-02-23',
                'user_id' => '4',
                'designation_id' => '15',
                'employment_id' => '5',
            ],
            [
                'id'    => '6',
                'fullname' => 'Wael El Taweel',
                'phone' => '20114713542',
                'mobile' => '20114713542',
                'joining_date' => '2019-09-01',
                'date_of_birth' => '1986-02-23',
                'user_id' => '6',
                'designation_id' => '2',
                'employment_id' => '4',
            ],
            [
                'id'    => '7',
                'fullname' => 'CFO',
                'phone' => '201069606061',
                'mobile' => '201069606061',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '7',
                'designation_id' => '8',
                'employment_id' => '2',
            ],
            [
                'id'    => '8',
                'fullname' => 'Ismael Effat',
                'phone' => '201069606061',
                'mobile' => '201069606061',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '8',
                'designation_id' => '0',
                'employment_id' => '17',
            ],
            [
                'id'    => '9',
                'fullname' => 'Ahmed Ayad',
                'phone' => '201068608084',
                'mobile' => '201068608084',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '9',
                'designation_id' => '0',
                'employment_id' => '15',
            ],
            [
                'id'    => '10',
                'fullname' =>  'Ahmed Emara',
                'phone' => '201228585555',
                'mobile' => '201228585555',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '10',
                'designation_id' => '0',
                'employment_id' => '16',
            ],
            [
                'id'    => '11',
                'fullname' =>  'Mohamed Saleh Hassan',
                'phone' => '971545843777',
                'mobile' => '971545843777',
                'joining_date' => '2019-09-22',
                'date_of_birth' => '1986-02-23',
                'user_id' => '13',
                'designation_id' => '14',
                'employment_id' => '8',
            ],
            [
                'id'    => '13',
                'fullname' =>  'Sherif Abd El',
                'phone' => '201003355949',
                'mobile' => '201003355949',
                'joining_date' => '2019-09-08',
                'date_of_birth' => '1986-02-23',
                'user_id' => '14',
                'designation_id' => '18',
                'employment_id' => '7',
            ],
            [
                'id'    => '14',
                'fullname' =>  'Ahmed Abd Elfatah',
                'phone' => '201090104345',
                'mobile' => '201090104345',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '15',
                'designation_id' => '21',
                'employment_id' => '10',
            ],
            [
                'id'    => '15',
                'fullname' =>  'Reda Mohamed El',
                'phone' => '201101004181',
                'mobile' => '201101004181',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '16',
                'designation_id' => '19',
                'employment_id' => '9',
            ],
            [
                'id'    => '19',
                'fullname' =>  'Ahmed Fawzy El',
                'phone' => '201023326488',
                'mobile' => '201023326488',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '20',
                'designation_id' => '21',
                'employment_id' => '11',
            ],
            [
                'id'    => '20',
                'fullname' =>  'Ghada Youssef Wagih',
                'phone' => '201023326488',
                'mobile' => '201023326488',
                'joining_date' => '2019-11-17',
                'date_of_birth' => '1982-04-06',
                'user_id' => '21',
                'designation_id' => '3',
                'employment_id' => '12',
            ],
            [
                'id'    => '21',
                'fullname' =>  'Norhan Mounir',
                'phone' => '201005516664',
                'mobile' => '201005516664',
                'joining_date' => '2019-01-19',
                'date_of_birth' => '1996-01-27',
                'user_id' => '22',
                'designation_id' => '25',
                'employment_id' => '14',
            ],
            [
                'id'    => '22',
                'fullname' =>  'Marwa Sayed Mostafa',
                'phone' => '01017286932',
                'mobile' => '01017286932',
                'joining_date' => '2019-01-18',
                'date_of_birth' => '1996-01-27',
                'user_id' => '23',
                'designation_id' => '26',
                'employment_id' => '13',
            ],
            [
                'id'    => '23',
                'fullname' =>  'Shrouk Elshal',
                'phone' => '01120176660',
                'mobile' => '01120176660',
                'joining_date' => '2020-02-16',
                'date_of_birth' => '1986-02-23',
                'user_id' => '24',
                'designation_id' => '27',
                'employment_id' => '18',
            ],
            [
                'id'    => '24',
                'fullname' =>  'Moaaz Radwan Ahmed',
                'phone' => '201016297228',
                'mobile' => '201016297228',
                'joining_date' => '2020-02-13',
                'date_of_birth' => '1995-05-22',
                'user_id' => '25',
                'designation_id' => '30',
                'employment_id' => '15',
            ],
            [
                'id'    => '26',
                'fullname' =>  'camps',
                'phone' => '201144836800',
                'mobile' => '201144836800',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '27',
                'designation_id' => '0',
                'employment_id' => 'NULL',
            ],
            [
                'id'    => '27',
                'fullname' =>  'Nehal Gamal',
                'phone' => '1554857786',
                'mobile' => '1554857786',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '28',
                'designation_id' => '0',
                'employment_id' => 'NULL',
            ],
            [
                'id'    => '28',
                'fullname' =>  'Ahmed Radwan',
                'phone' => '201006143107',
                'mobile' => '201006143107',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '29',
                'designation_id' => '23',
                'employment_id' => '0',
            ],
            [
                'id'    => '29',
                'fullname' =>  'Mr mahmoud abdalla',
                'phone' => '1091032423',
                'mobile' => '1091032423',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '30',
                'designation_id' => '0',
                'employment_id' => 'NULL',
            ],
            [
                'id'    => '30',
                'fullname' =>  'Mohammed Ibrahim Hamed',
                'phone' => '201028824642',
                'mobile' => '201028824642',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1994-10-01',
                'user_id' => '31',
                'designation_id' => '15',
                'employment_id' => '31',
            ],
            [
                'id'    => '31',
                'fullname' =>  'Ahmed Faruk',
                'phone' => '201062164867',
                'mobile' => '201062164867',
                'joining_date' => '2020-05-17',
                'date_of_birth' => '1986-02-23',
                'user_id' => '32',
                'designation_id' => '0',
                'employment_id' => 'NULL',
            ],
            [
                'id'    => '32',
                'fullname' =>  'Mahmoud Saied El',
                'phone' => '201123408535',
                'mobile' => '201123408535',
                'joining_date' => '2020-06-01',
                'date_of_birth' => '1995-06-29',
                'user_id' => '33',
                'designation_id' => '29',
                'employment_id' => '33',
            ],
            [
                'id'    => '33',
                'fullname' =>  'Mostafa Gamal Abdelsatar',
                'phone' => '201097034883',
                'mobile' => '201097034883',
                'joining_date' => '2020-08-13',
                'date_of_birth' => '1996-06-01',
                'user_id' => '34',
                'designation_id' => '23',
                'employment_id' => '34',
            ],
            [
                'id'    => '34',
                'fullname' =>  'ShadyOsama Fawzy',
                'phone' => '201097034883',
                'mobile' => '201097034883',
                'joining_date' => '2020-08-10',
                'date_of_birth' => '1998-02-28',
                'user_id' => '35',
                'designation_id' => '26' ,
                'employment_id' => 26,
                // 'set_time_id' => '35',
            ],
            [
                'id'    => '35',
                'fullname' =>  'Ali Emad Eldamiry',
                'phone' => '201097034883',
                'mobile' => '201097034883',
                'joining_date' => '2020-08-10',
                'date_of_birth' => '1996-06-03',
                'user_id' => '36',
                'designation_id' => '31',
                'employment_id' => '36',
            ]
        ];

        AccountDetail::insert($accountDetails);
    }
}
