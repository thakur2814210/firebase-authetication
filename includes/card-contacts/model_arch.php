<?php

class FilterableCard {

	public $card_id;
	public $card_name;
	public $card_type;
	public $canvas_front;
	public $company_name;
	public $department_name;
	public $position;
	public $first_name;
	public $last_name;
	public $full_name;
	public $distributed_brand;
	public $category;
	public $sub_category;
	public $phone;
	public $mobile;
	public $email_address;
	public $title;
	public $website_link;
	public $landscape;
	public $rating;
	public $average_rating;

	public function __construct(
	$card_id, $card_name, $card_type, $canvas_front, $company_name, $department_name, $position, $first_name, $last_name, $distributed_brand, $category, $sub_category, $phone, $mobile, $email_address, $title, $website_link, $landscape, $rating, $average_rating) {
		$this->card_id = $card_id;
		$this->card_name = $card_name;
		$this->card_type = $card_type;
		$this->canvas_front = $canvas_front;
		$this->company_name = $company_name;
		$this->department_name = $department_name;
		$this->position = $position;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->full_name = $first_name . ' ' . $last_name;
		$this->distributed_brand = $distributed_brand;
		$this->category = $category;
		$this->sub_category = $sub_category;
		$this->phone = $phone;
		$this->mobile = $mobile;
		$this->email_address = $email_address;
		$this->title = $title;
		$this->website_link = $website_link;
		$this->rating = $rating;
		$this->average_rating = $average_rating;

		if ($landscape) {
			$this->landscape = FALSE;
			$this->orientation = "Landscape";
		} else {
			$this->landscape = TRUE;
			$this->orientation = "Portrait";
		}
	}

}

class CardFolder {

	public $card_id;
	public $folder_id;
	public $folder_name;

	public function __construct($card_id, $folder_id, $folder_name) {
		$this->card_id = $card_id;
		$this->folder_id = $folder_id;
		$this->folder_name = $folder_name;
	}

}

class SearchedCard {

	public $card_id;
	public $card_name;
	public $canvas_front;
	public $canvas_back;
	public $links_front;
	public $links_back;
	public $company_name;
	public $department_name;
	public $position;
	public $first_name;
	public $last_name;
	public $full_name;
	public $card_type;
	public $profile_image;
	public $distributed_brand;
	public $category;
	public $sub_category;
	public $phone;
	public $mobile;
	public $email_address;
	public $title;
	public $website_link;
	public $settings;
	public $seen_in_user_folder;
	public $link_status;
	public $your_contact;
	public $can_share;
	public $need_approval;
	public $pending_request;
	public $requires_reciprocity;
	public $landscape;
	public $average_rating;
	public $your_rating;
	public $allow_rating;
	public $bcn;

	public function __construct(
	$card_id, $card_name, $card_type, $canvas_front, $canvas_back, $links_front, $links_back, $company_name, $department_name, $position, $first_name, $last_name, $profile_image, $distributed_brand, $category, $sub_category, $phone, $mobile, $email_address, $title, $website_link, $landscape, $link_status, $settings, $seen_in_user_folder, $average_rating, $bcn) {
		$this->card_id = $card_id;
		$this->card_name = $card_name;
		$this->card_type = $card_type;
		$this->canvas_front = $canvas_front;
		$this->canvas_back = $canvas_back;
		$this->links_front = $links_front;
		$this->links_back = $links_back;
		$this->company_name = $company_name;
		$this->department_name = $department_name;
		$this->position = $position;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->full_name = $first_name . ' ' . $last_name;
		$this->profile_image = $profile_image;
		$this->distributed_brand = $distributed_brand;
		$this->category = $category;
		$this->sub_category = $sub_category;
		$this->phone = $phone;
		$this->mobile = $mobile;
		$this->email_address = $email_address;
		$this->title = $title;
		$this->website_link = $website_link;
		$this->settings = $settings;
		$this->seen_in_user_folder = $seen_in_user_folder;
		$this->average_rating = $average_rating;
		$this->allow_rating = false;
		$this->bcn = $bcn;

		//set defaults
		//more should be set here, than these that are set at the moment
		$this->pending_request = FALSE;
		//$this->requires_reciprocity = FALSE;

		if ($link_status == "NONE") {
			$this->your_contact = FALSE;
		} elseif ($link_status == "ACCEPTED") {
			$this->your_contact = TRUE;
		} elseif ($link_status == "REQUESTED") {
			$this->pending_request = TRUE;
		} else {
			$this->your_contact = FALSE;
		}

		if ($this->settings->settings_blob["share_among_users"] == "1") {
			$this->can_share = TRUE;
		} elseif ($this->settings->settings_blob["share_among_users"] == "0") {
			$this->can_share = FALSE;
		} else {
			$this->can_share = FALSE;
		}

		if ($this->card_type == "Personal" || $this->card_type == "Corporate") {

			if ($this->settings->settings_blob["need_approval"] == "1") {
				$this->need_approval = TRUE;
			} elseif ($this->settings->settings_blob["need_approval"] == "0") {
				$this->need_approval = FALSE;
			} else {
				$this->need_approval = FALSE;
			}

			if ($settings->settings_blob["requires_reciprocity"] == "1") {
				$this->requires_reciprocity = TRUE;
			} elseif ($settings->settings_blob["requires_reciprocity"] == "0") {
				$this->requires_reciprocity = FALSE;
			} else {
				$this->requires_reciprocity = FALSE;
			}
			$this->allow_rating = TRUE;
		} elseif ($this->card_type == "Professional" || $this->card_type == "Product") {
			$this->need_approval = FALSE;
			if ($settings->settings_blob["allow_rating"] == "1") {
				$this->allow_rating = TRUE;
			}
		} else {
			$this->need_approval = FALSE;
			//panic
		}

		if ($landscape) {
			$this->landscape = FALSE;
			$this->orientation = "Landscape";
		} else {
			$this->landscape = TRUE;
			$this->orientation = "Portrait";
		}
	}

}

class SearchedCardInFolder {

	public $card_id;
	public $card_name;
	public $canvas_front;
	public $canvas_back;
	public $links_front;
	public $links_back;
	public $company_name;
	public $department_name;
	public $position;
	public $first_name;
	public $last_name;
	public $full_name;
	public $card_type;
	public $profile_image;
	public $distributed_brand;
	public $category;
	public $sub_category;
	public $phone;
	public $mobile;
	public $email_address;
	public $title;
	public $website_link;
	public $settings;
	public $seen_in_user_folder;
	public $link_status;
	public $your_contact;
	public $can_share;
	public $need_approval;
	public $pending_request;
	public $requires_reciprocity;
	public $landscape;
	public $average_rating;
	public $your_rating;
	public $allow_rating;
	public $bcn;

	public function __construct(
	$card_id, $card_name, $card_type, $canvas_front, $canvas_back, $links_front, $links_back, $company_name, $department_name, $position, $first_name, $last_name, $profile_image, $distributed_brand, $category, $sub_category, $phone, $mobile, $email_address, $title, $website_link, $landscape, $link_status, $settings, $seen_in_user_folder, $your_rating, $average_rating, $bcn) {
		$this->card_id = $card_id;
		$this->card_name = $card_name;
		$this->card_type = $card_type;
		$this->canvas_front = $canvas_front;
		$this->canvas_back = $canvas_back;
		$this->links_front = $links_front;
		$this->links_back = $links_back;
		$this->company_name = $company_name;
		$this->department_name = $department_name;
		$this->position = $position;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->full_name = $first_name . ' ' . $last_name;
		$this->profile_image = $profile_image;
		$this->distributed_brand = $distributed_brand;
		$this->category = $category;
		$this->sub_category = $sub_category;
		$this->phone = $phone;
		$this->mobile = $mobile;
		$this->email_address = $email_address;
		$this->title = $title;
		$this->website_link = $website_link;
		$this->settings = $settings;
		$this->seen_in_user_folder = $seen_in_user_folder;
		$this->average_rating = $average_rating;
		$this->your_rating = $your_rating;
		$this->allow_rating = false;
		$this->bcn = $bcn;

		//set defaults
		//more should be set here, than these that are set at the moment
		$this->pending_request = FALSE;
		//$this->requires_reciprocity = FALSE;

		if ($link_status == "NONE") {
			$this->your_contact = FALSE;
		} elseif ($link_status == "ACCEPTED") {
			$this->your_contact = TRUE;
		} elseif ($link_status == "REQUESTED") {
			$this->pending_request = TRUE;
		} else {
			$this->your_contact = FALSE;
		}

		if ($this->settings->settings_blob["share_among_users"] == "1") {
			$this->can_share = TRUE;
		} elseif ($this->settings->settings_blob["share_among_users"] == "0") {
			$this->can_share = FALSE;
		} else {
			$this->can_share = FALSE;
		}

		if ($this->card_type == "Personal" || $this->card_type == "Corporate") {

			if ($this->settings->settings_blob["need_approval"] == "1") {
				$this->need_approval = TRUE;
			} elseif ($this->settings->settings_blob["need_approval"] == "0") {
				$this->need_approval = FALSE;
			} else {
				$this->need_approval = FALSE;
			}

			if ($settings->settings_blob["requires_reciprocity"] == "1") {
				$this->requires_reciprocity = TRUE;
			} elseif ($settings->settings_blob["requires_reciprocity"] == "0") {
				$this->requires_reciprocity = FALSE;
			} else {
				$this->requires_reciprocity = FALSE;
			}
			$this->allow_rating = TRUE;
		} elseif ($this->card_type == "Professional" || $this->card_type == "Product") {
			$this->need_approval = FALSE;
			if ($settings->settings_blob["allow_rating"] == "1") {
				$this->allow_rating = TRUE;
			}
		} else {
			$this->need_approval = FALSE;
			//panic
		}

		if ($landscape) {
			$this->landscape = FALSE;
			$this->orientation = "Landscape";
		} else {
			$this->landscape = TRUE;
			$this->orientation = "Portrait";
		}
	}

}
