<?phpclass CardLink{	public $card_link;	public $user_requested_by;	public $card_id_requested;	public $link_status;	public $date_requested;	public $date_accepted;	public function __construct( $card_link, $user_requested_by, $card_id_requested, $link_status, $date_requested, $date_accepted )	{		$this->card_link = $card_link;		$this->user_requested_by = $user_requested_by;		$this->card_id_requested = $card_id_requested;		$this->link_status = $link_status;		$this->date_requested = $date_requested;		$this->date_accepted = $date_accepted;	}}class CardLinkComposite{	public $surname;	public $first_name;	public $company_name;	public $date_added;	public function __construct( $surname, $first_name, $company_name, $date_added )	{		$this->surname = $surname;		$this->first_name = $first_name;		$this->company_name = $company_name;		$this->date_added = $date_added;	}}