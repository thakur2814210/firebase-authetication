<?php

require_once('../../session_setup.php');

//cadd to db
require_once '../database_config.php';

//which table to select
$from = trim( $_REQUEST[ 'to' ] );

// get personal data
if ( $from == 'personal' )
{

	$title = trim( $_REQUEST[ 'title' ] );
	$last_name = trim( $_REQUEST[ 'last_name' ] );
	$first_name = trim( $_REQUEST[ 'first_name' ] );
	$phone = trim( $_REQUEST[ 'phone' ] );
	$mobile = trim( $_REQUEST[ 'mobile' ] );
	$website_link = trim( $_REQUEST[ 'website_link' ] );
	$email_address = trim( $_REQUEST[ 'email_address' ] );
	$personal_address_1 = trim( $_REQUEST[ 'personal_address_1' ] );
	$personal_address_2 = trim( $_REQUEST[ 'personal_address_2' ] );
	$personal_city = trim( $_REQUEST[ 'personal_city' ] );
	$personal_country = trim( $_REQUEST[ 'personal_country' ] );
	$personal_post_code = trim( $_REQUEST[ 'personal_post_code' ] );


	$result = mysqli_query( $conn, "SELECT user_id, email_address, verified FROM user WHERE email_address = '" . $email_address . "' OR company_email_address = '" . $email_address . "'" );
	if ( $result )
	{
		$check_email = mysqli_fetch_row( $result );
		if ( $check_email[ 0 ] != null && $check_email[ 0 ] != $_SESSION[ 'user_id' ] && $check_email[2] == 0)
		{
			$msg = 'exists';
		}
		else
		{

			$update = mysqli_query( $conn, "UPDATE user
            SET  title = '" . $title . "'
                ,last_name = '" . $last_name . "'
                ,first_name = '" . $first_name . "'
                ,phone = '" . $phone . "'
                ,mobile = '" . $mobile . "'
                ,email_address = '" . $email_address . "'
                ,website_link = '" . $website_link . "'
            WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );

//            $msg = 'success';
//		}

			if ( $personal_country != null )
			{

				$result = mysqli_query( $conn, "SELECT user_id, personal_address_id FROM user WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );
				$check_address = mysqli_fetch_row( $result );

				if ( $check_address[ 1 ] != null && $check_address[ 1 ] != "" )
				{

					$personal_address_id = $check_address[ 1 ];
					mysqli_query( $conn, "update address set address_1 = '" . $personal_address_1 . "', address_2 = '" . $personal_address_2 . "', city = '" . $personal_city . "', country_id = '" . $personal_country . "', post_code = '" . $personal_post_code . "' where address_id = '" . $personal_address_id . "'" );

					$update = mysqli_query( $conn, "UPDATE user
            SET  personal_country_id = '" . $personal_country . "'
            WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );

					$msg = 'success';
				}
				else
				{

					$personal_address_id = uniqid();
					mysqli_query( $conn, "insert into address (address_id, address_1, address_2, city, country_id, post_code) values ('" . $personal_address_id . "', '" . $personal_address_1 . "', '" . $personal_address_2 . "', '" . $personal_city . "', '" . $personal_country . "', '" . $personal_post_code . "')" );

					$update = mysqli_query( $conn, "UPDATE user
            SET  personal_address_id = '" . $personal_address_id . "'
                ,personal_country_id = '" . $personal_country . "'
            WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );

					$msg = 'success';
				} // end check address
			} // end check country
		}
	}
}
else
{

	// get company data
	$company_name = trim( $_REQUEST[ 'company_name' ] );
	$department_name = trim( $_REQUEST[ 'department_name' ] );
	$position = trim( $_REQUEST[ 'position' ] );
	$corporate_code = trim( $_REQUEST[ 'corporate_code' ] );
	$company_phone = trim( $_REQUEST[ 'company_phone' ] );
	$company_mobile = trim( $_REQUEST[ 'company_mobile' ] );
	$company_website_link = trim( $_REQUEST[ 'company_website_link' ] );
	$company_address_1 = trim( $_REQUEST[ 'company_address_1' ] );
	$company_address_2 = trim( $_REQUEST[ 'company_address_2' ] );
	$company_city = trim( $_REQUEST[ 'company_city' ] );
	$company_country = trim( $_REQUEST[ 'company_country' ] );
	$company_post_code = trim( $_REQUEST[ 'company_post_code' ] );
	$company_email_address = trim( $_REQUEST[ 'company_email_address' ] );

	if ( $company_country != null )
	{

		$result = mysqli_query( $conn, "SELECT user_id, company_address_id FROM user WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );
		$check_address = mysqli_fetch_row( $result );

		if ( $check_address[ 1 ] != null && $check_address[ 1 ] != "" )
		{

			$company_address_id = $check_address[ 1 ];
			mysqli_query( $conn, "update address set address_1 = '" . $company_address_1 . "', address_2 = '" . $company_address_2 . "', city = '" . $company_city . "', country_id = '" . $company_country . "', post_code = '" . $company_post_code . "' where address_id = '" . $company_address_id . "'" );

			$update = mysqli_query( $conn, "UPDATE user
            SET  company_country_id = '" . $company_country . "'
            WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );

			$msg = 'success';
		}
		else
		{

			$company_address_id = uniqid();
			mysqli_query( $conn, "insert into address (address_id, address_1, address_2, city, country_id, post_code) values ('" . $company_address_id . "', '" . $company_address_1 . "', '" . $company_address_2 . "', '" . $company_city . "', '" . $company_country . "', '" . $company_post_code . "')" );

			$update = mysqli_query( $conn, "UPDATE user
            SET  company_address_id = '" . $company_address_id . "'
            ,company_country_id = '" . $company_country . "'
            WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );

			$msg = 'success';
		} // end check address

		$result = mysqli_query( $conn, "SELECT user_id, company_email_address, email_address FROM user WHERE company_email_address = '" . $company_email_address . "' OR email_address = '" . $company_email_address . "'" );
		if ( $result )
		{
			$check_email = mysqli_fetch_row( $result );

			if ( $check_email[ 0 ] != null && $check_email[ 0 ] != $_SESSION[ 'user_id' ] )
			{

				$update = mysqli_query( $conn, "UPDATE user
                SET  company_name = '" . $company_name . "'
                    ,department_name = '" . $department_name . "'
                    ,position = '" . $position . "'
                    ,corporate_code = '" . $corporate_code . "'
                    ,company_phone = '" . $company_phone . "'
                    ,company_mobile = '" . $company_mobile . "'
                    ,company_website_link = '" . $company_website_link . "'
                WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );

				$msg = 'exists';
			}
			else
			{

				$update = mysqli_query( $conn, "UPDATE user
                SET  company_name = '" . $company_name . "'
                    ,department_name = '" . $department_name . "'
                    ,position = '" . $position . "'
                    ,corporate_code = '" . $corporate_code . "'
                    ,company_phone = '" . $company_phone . "'
                    ,company_mobile = '" . $company_mobile . "'
                    ,company_email_address = '" . $company_email_address . "'
                    ,company_website_link = '" . $company_website_link . "'
                WHERE user_id = '" . $_SESSION[ 'user_id' ] . "'" );

				$msg = 'success';
			}
		}
	} // end check country
}

if ( isset( $msg ) )
{
	echo $msg;
}
