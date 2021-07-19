<?php

class YourCard
{
  public $card_id;
  public $card_type;
  public $card_name;
  public $canvas_back;
  public $canvas_front;
  public $widgets_front;
  public $widgets_back;
  public $links_front;
  public $links_back;
  public $orientation;
  public $width;
  public $height;
  public $default_card;
  public $bcn;

  public function __construct($card_id, $card_type, $card_name,$canvas_back,$canvas_front,$widgets_front,$widgets_back,$links_front,$links_back,$landscape,$default_card,$bcn)
  {
		date_default_timezone_set( 'Europe/London' );
		$now =  date( "YmdTHis" ) . substr( ( string ) microtime(), 1, 8 );
    $this->card_id = $card_id;
    $this->card_type = $card_type;
    $this->card_name = $card_name;
    $this->canvas_back = trim($canvas_back.'?'.$now);
    $this->canvas_front = trim($canvas_front.'?'.$now); 
    $this->widgets_front = $widgets_front;
    $this->widgets_back = $widgets_back;
    $this->links_front = $links_front;
    $this->links_back = $links_back;
    $this->default_card = $default_card;
    $this->bcn = $bcn;

    if ($this->default_card == $this->card_id) {
        $this->default_card = 'fav';
    }else{
		$this->default_card = '';
	}

    if ($landscape != null) {
        if ($landscape == '1') { 
            $this->orientation = 'landscape'; 
        } else { 
            $this->orientation = 'portrait'; 
        }; 
    };
    if ($this->orientation == 'landscape') { 
        $this->width = '340px'; 
        $this->height = '187px'; 
    } else { 
        $this->width = '187px'; 
        $this->height = '340px'; 
    };

  }
}
