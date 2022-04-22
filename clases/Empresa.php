<?php 

class Empresa {
    
    protected $id;
    protected $company;
    protected $investment;
    protected $date;
    protected $active;
    protected $info;

    public function __construct($id, $company, $investment, $date, $active, $info) {
        $this->id = $id;
        $this->company = $company;
        $this->investment = $investment;
        $this->date = $date;
        $this->active = $active;
        $this->info = $info;
    }

    // Getters
    public function getId(){
        return $this->id;
    }

    public function getCompany(){
        return $this->company;
    }

    public function getInvestment(){
        return $this->investment;
    }

    public function getDate(){
        return $this->date;
    }

    public function getActive(){
        return $this->active;
    }

    public function getInfo(){
        return $this->info;
    }
}

?>