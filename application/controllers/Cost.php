<?php

class Cost extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Cost_model');
        $this->check_login();
    }

    /**
     * 默认 - 页面
     */
    public function index()
    {
       $this->lists();
    }

    public function add(){
        if (!$this->getData('userInfo')) {
            $this->load->view('user/login');
            return;
        }
        $this->setData('costType_list', $this->Cost_model->get_costType_list());
        $this->setData('UserName_list', $this->Cost_model->get_UserName_list());
        $this->load->view('cost/add', $this->getData());
    }

    /**
     * cost列表 - 页面
     * @param int $mouth
     */
    public function lists($mouth = 0)
    {
        $this->setData('UserName_list', $this->Cost_model->get_UserName_list());
        $this->setData('cost', $this->deal_cost_list($this->Cost_model->get_cost_list()));
        $this->load->view('cost/list', $this->getData());
    }

    /**
     * 获取指定月份的cost_list
     * @param int $month 月份
     */
    public function ajax_month_list($month = 1)
    {
        $this->setData('UserName_list', $this->Cost_model->get_UserName_list());
        $this->setData('cost', $this->deal_cost_list($this->Cost_model->get_month_list($month), 'day'));
        $this->setData('month', $month);
        $this->load->view('cost/month_list', $this->getData());
    }

    /**
     * 获取指定日期的cost_list
     * @param int $month 月
     * @param int $day 日
     */
    public function ajax_day_list($month = 1, $day = 1)
    {
//        var_dump_it($this->Cost_model->get_day_list($month,$day));
        $this->setData('cost', $this->Cost_model->get_day_list($month, $day));
        $this->setData('month', $month);
        $this->setData('day', $day);
        $this->load->view('cost/day_list', $this->getData());
    }

    /**
     * 刷新 - 空白
     */
    public function ajax_empty()
    {

    }

    /**
     * 梳理cost_list数据
     * @param $data 查询得到的原始cost_list数据
     * @param $type
     * @@return 处理后数据
     */
    public function deal_cost_list($data, $type = 'month')
    {
        $result = array('list' => array(), 'total' => array());
        foreach ($data['list'] as $value) {
            $result['list'][$value[$type]][$value['UseName']] = $value['num'];
            $result['list'][$value[$type]]['total'] = get_array_value('total', $result['list'][$value[$type]]) + $value['num'];
        }
        foreach ($data['total'] as $value) {
            $result['total'][$value['UseName']] = $value['num'];
            $result['total']['total'] = get_array_value('total', $result['total']) + $value['num'];
        }
        return $result;
    }

    public function do_add()
    {
        if (!$this->getData('userInfo')) {
            $this->load->view('user/login');
            return;
        }
        if (!$this->input->post() || !$this->input->post('money')) {
            $this->load->view('cost/add');
        }
        if ($this->Cost_model->add_cost($this->input->post(), $this->getData('userInfo')->UserId)) {
            c_exit(1,'成功');
        } else {
            c_exit(0,'失败');
        }
    }

    public function add_cost_type()
    {
        if (!$this->input->post('typeName')) {
            c_exit(0, '缺少类型名称');
        }
        $data = array(
            'typeName' => $this->input->post('typeName'),
            'CreateUserName' => $this->getData('userInfo')->UserName,
            'CreateTime' => date('Y-m-d H:i:s', time())
        );
        $result = $this->Cost_model->add_cost_type($data);
        $result ? c_exit(1, $result) : c_exit(0, '创建失败');
    }

    public function do_add_type()
    {

    }

    public function check_login()
    {
        if (!$this->getData('userInfo')) {
            redirect(site_url(array('user/login')));
        }
    }

    public function check_num()
    {
        if (is_numeric($this->input->get('Money'))) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }

    public function test()
    {
        $result = $this->ajax_day_list(7, 20);
        var_dump_it($result);
    }


}

 