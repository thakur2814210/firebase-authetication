<?php
class FilterableCard
{

    public $card_id;
    public $card_name;
    public $canvas_front;
    public $company_name;
    public $department_name;
    public $position;
    public $first_name;
    public $last_name;
    public $full_name;
    public $card_type;
    public $distributed_brand;
    public $category;
    public $sub_category;
    public $phone;
    public $mobile;
    public $email_address;
    public $title;
    public $website_link;

    public function __construct(
    $card_id,$card_name,$card_type,$canvas_front,
    $company_name,$department_name,$position,
    $first_name,$last_name,
    $distributed_brand,$category,$sub_category,
    $phone,$mobile,$email_address,$title,$website_link) 
    {
        $this->card_id = $card_id;
        $this->card_name = $card_name;
        $this->card_type = $card_type;
        $this->canvas_front = $canvas_front;
        $this->company_name = $company_name;
        $this->department_name = $department_name;
        $this->position = $position;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->full_name = $first_name .' '.$last_name;
        $this->distributed_brand = $distributed_brand;
        $this->category = $category;
        $this->sub_category = $sub_category;
        $this->phone = $phone;
        $this->mobile = $mobile;
        $this->email_address = $email_address;
        $this->title = $title;
        $this->website_link = $website_link;
    }
}
class CardFolder
{
    public $card_id;
    public $folder_id;
    public $folder_name;

    public function __construct($card_id,$folder_id,$folder_name) 
    {
        $this->card_id = $card_id;
        $this->folder_id = $folder_id;
        $this->folder_name = $folder_name;
    }
}
?>