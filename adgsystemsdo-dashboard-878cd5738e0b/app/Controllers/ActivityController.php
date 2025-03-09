<?php


namespace App\Controllers;
use App\Common\ReportsFunctions;
use App\Http\Request;
use App\Legacy\General;
use App\Services\ActivityService;

class ActivityController extends BaseController
{

    public function index(){
        return $this->view('reports.activityReport');
    }

    public function getCountActivity(){
        $filters = [
            'seller_code' =>   Request::body()->get('seller'),
            'rango' =>     Request::body()->get('rango'),
            'customer_code' =>       Request::body()->get('customer'),
        ];
        $report = new General();

        $activity =  $report->ActivityCount($filters);

        return $this->jsonResponse($activity);

    }

    public function getActivityData(){

        $filters = [
            'seller_code' =>   Request::body()->get('seller'),
            'rango' =>     Request::body()->get('rango'),
            'customer_code' =>       Request::body()->get('customer'),
        ];

        $report = new ActivityService();

        $activity =  $report->getActivityRecent($filters);

        return $this->jsonResponse($activity);
    }

    public function getSellersActivity(){
        $isSeller = Request::body()->get('seller');
        $filters = [
            'rango' =>     Request::body()->get('rango'),
            'seller_code' =>  Request::body()->get('seller'),
            'customer_code' =>  Request::body()->get('customer'),
        ];
        $report = new General();
        $activity =  $report->SellersActivity($filters);
        return $this->jsonResponse($activity);
    }

    public function ActivityReportData()
    {
        $params = [
            'seller_code' =>   Request::body()->get('seller'),
            'rango' =>     Request::body()->get('rango'),
            'customer_code' =>       Request::body()->get('customer'),
        ];

        $activity = new ActivityService();

        $activityData =  $activity->printActivity($params);

        return $this->jsonResponse($activityData);
    }

    public function indexActivity(){
        return $this->view('reports.activityReportPrint');
    }


}
