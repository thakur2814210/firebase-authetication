<?php
class CardStatusPermissions
{

    public $card_id;
    public $card_type;
    public $your_contact;
    public $can_share;
    public $need_approval;
    public $pending_request;
    public $requires_reciprocity;
    public $allow_rating;

    public function __construct($card_id,$card_type,$link_status,$settings) 
    {
        $this->card_id = $card_id;
        $this->card_type = $card_type;
        
        $this->requires_reciprocity = FALSE;
        $this->pending_request = FALSE;
        $this->allow_rating = FALSE;

        if($link_status == "NONE"){
            $this->your_contact = FALSE;
        }elseif($link_status == "ACCEPTED"){
            $this->your_contact = TRUE;
        }elseif($link_status == "REQUESTED"){
            $this->pending_request = TRUE;
        }else{
            $this->your_contact = FALSE;
        }
        
        if($settings->settings_blob["share_among_users"] == "1"){
            $this->can_share = TRUE;
        }elseif($settings->settings_blob["share_among_users"] == "0"){
            $this->can_share = FALSE;
        }else{
            $this->can_share = FALSE;
        }
        
        if($this->card_type == "Personal" || $this->card_type == "Corporate"){
            //init correct value for need_approval
            if($settings->settings_blob["need_approval"] == "1"){
                $this->need_approval = TRUE;
            }elseif($settings->settings_blob["need_approval"] == "0"){
                $this->need_approval = FALSE;
            }else{
                $this->need_approval = FALSE;
            }
            //init correct value for requires_reciprocity
            if($settings->settings_blob["requires_reciprocity"] == "1"){
                $this->requires_reciprocity = TRUE;
            }elseif($settings->settings_blob["requires_reciprocity"] == "0"){
                $this->requires_reciprocity = FALSE;
            }else{
                $this->requires_reciprocity = FALSE;
            }
            $this->allow_rating = TRUE;
        }elseif($this->card_type == "Professional" || $this->card_type == "Product"){
            $this->need_approval = FALSE;
            if($settings->settings_blob["allow_rating"] == "1"){
                $this->allow_rating = TRUE;
            }
        }else{
            $this->need_approval = FALSE;
        }
    }
}
?>
