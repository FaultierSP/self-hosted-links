import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
//import axios from 'axios';
import React, {useEffect,useState} from "react";

import dayjs from 'dayjs';
//https://day.js.org/docs/en/manipulate/subtract

import {DatePicker,Modal,Skeleton,Table} from 'antd';

import {ResponsiveContainer, LabelList, LineChart, Line, BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend} from 'recharts';
//https://recharts.org/en-US/api/LineChart

export default function Dashboard({ auth }) {
    const dateFormat='DD.MM.YYYY';
   
    const {RangePicker} = DatePicker;

    const [visitorsGraphData,setVisitorsGraphData]=useState([]);
    const [referrersGraphData,setReferrersGraphData]=useState([]);
    const [clicksGraphData,setClicksGraphData]=useState([]);
    const [dateFrom,setDateFrom]=useState(dayjs().subtract(7,'day'));
    const [dateTo,setDateTo]=useState(dayjs());

    const [isReferrerInfoModalOpen,setIsReferrerInfoModalOpen]=useState(false);
    const [referrerModalTitle,setReferrerModalTitle]=useState("");
    const [referrerModalShowSkeleton,setReferrerModalShowSkeleton]=useState(true);
    const [referrerModalTableData,setReferrerModalTableData]=useState([]);
    const referrerModalTableColumns=[{
      title: 'Counter',
      dataIndex: 'counter',
      sorter: (a,b) => a.counter-b.counter,
    },
    {
      title: 'Visited on',
      dataIndex: 'visit_date',
      render: (visit_date) => dayjs(visit_date,"YYYY-MM-DD").format(dateFormat),
      sorter: (a,b) => new Date(a.visit_date) - new Date(b.visit_date),
    },
    {
      title: 'From URL',
      dataIndex: 'referrer_url',
      render: (referrer_url) => <a href={referrer_url}>{referrer_url}</a>
    }];

    const getVisits = (dateObjects) => {
      var dateFromForRequest=dateFrom.format('YYYY-MM-DD');
      var dateToForRequest=dateTo.format('YYYY-MM-DD');
      
      if(dateObjects) {
        dateFromForRequest=dateObjects[0].format('YYYY-MM-DD');
        dateToForRequest=dateObjects[1].format('YYYY-MM-DD');
      }

      axios.get('/api/analytics/visitors',{params:{
        from: dateFromForRequest,
        to: dateToForRequest
      }}).then(function (response) {
        setVisitorsGraphData(response.data);
      }).catch(error => {console.log(error)});

      axios.get('/api/analytics/referrers',{params:{
        from: dateFromForRequest,
        to: dateToForRequest
      }}).then(function (response) {
        setReferrersGraphData(response.data);
      }).catch(error => {console.log(error)});

      axios.get('/api/analytics/clicks',{params:{
        from: dateFromForRequest,
        to: dateToForRequest
      }}).then(function (response) {
        setClicksGraphData(response.data);
      }).catch(error => {console.log(error)});
    }

    const showInfoAboutReferrer = (data,index) => {
      setReferrerModalTitle("Clicks from "+data['host']);

      axios.get('api/analytics/referrers_paths',{params:{id: data['id']}}).
        then(function (response){
          response.data.forEach(object=>{object.referrer_url='https://'+data['host']+'/'+object.path+((object.query===null) ? '' : '?'+object.query);});
          setReferrerModalTableData(response.data);
          setReferrerModalShowSkeleton(false);
        }).catch(error => {console.log(error)});

      setIsReferrerInfoModalOpen(true);
    }

    const closeInfoAboutReferrer = () => {
      setIsReferrerInfoModalOpen(false);
      setReferrerModalTitle("");
      setReferrerModalShowSkeleton(true);
      setReferrerModalTableData([]);
    }

    useEffect(() => {
      getVisits();
    }, []);
    
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Dashboard" />

            <Modal  title={referrerModalTitle}
                    open={isReferrerInfoModalOpen}
                    onOk={closeInfoAboutReferrer}
                    onCancel={closeInfoAboutReferrer}
                    autoFocusButton={null}
                    cancelButtonProps={{ style: { display: 'none' } }}>
              <Skeleton active paragraph loading={referrerModalShowSkeleton}/>
              <Table  dataSource={referrerModalTableData}
                      columns={referrerModalTableColumns} />
            </Modal>
            
            <div className="py-2">
              <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                  <div className="p-6 text-gray-900 flex items-center justify-end">
                    <RangePicker  defaultValue={[dateFrom,dateTo]}
                                  allowClear={false}
                                  format={dateFormat}
                                  onChange={getVisits}/>
                  </div>
                </div>
              </div>
            </div>

            <div className="py-2">
                <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 flex justify-between">
                            <ResponsiveContainer height={350} width="30%">
                              <p>Total visits</p>
                              <LineChart data={visitorsGraphData}>
                                  <CartesianGrid strokeDasharray="3 3" />
                                  <XAxis dataKey="date" />
                                  <YAxis />
                                  <Tooltip />
                                  <Legend />
                                  <Line name="Total loads" type="monotone" dataKey="total_loads" stroke="#8884d8" />
                                  <Line name="Unique hashes" type="monotone" dataKey="unique_hashes" stroke="#82ca9d" />
                              </LineChart>
                            </ResponsiveContainer>
                            <ResponsiveContainer height={350} width="30%">
                              <p>Referrers</p>
                              <BarChart data={referrersGraphData} layout='vertical'>
                              <CartesianGrid strokeDasharray="3 3" />
                                  <XAxis type='number' />
                                  <YAxis type='category' dataKey="host" width={0} />
                                  <Tooltip />
                                  <Bar dataKey="sum" name="Total visits" fill="#8884d8" onClick={showInfoAboutReferrer}>
                                    <LabelList dataKey="host" position="insideLeft" style={{fill:"white"}} />
                                  </Bar>
                              </BarChart>
                            </ResponsiveContainer>
                            <ResponsiveContainer height={350} width="30%">
                              <p>Clicks</p>
                              <BarChart data={clicksGraphData} layout='vertical'>
                              <CartesianGrid strokeDasharray="3 3" />
                                  <XAxis type='number' />
                                  <YAxis type='category' dataKey="name" width={0} />
                                  <Tooltip />
                                  <Bar dataKey="sum" name="Total clicks" fill="#8884d8">
                                    <LabelList dataKey="name" position="insideLeft" style={{fill:"white"}} />
                                  </Bar>
                              </BarChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
