<?
    $result = array();
    $method = trim(strip_tags(htmlspecialchars($_GET['method'])));
    if(!$method){
        $result['error'] = 'Не указан метод';
        die(json_encode($result));
    }

    switch($method){
        case 'get_cities':
            $result['cities_response'] = get_cities();
        break;
        case 'get_warehouses':
            $cityRef = trim(strip_tags(htmlspecialchars($_GET['city'])));
            $result['warehouses_response'] = get_warehouses($cityRef);
        break;
        default:
            $result['error'] = 'Неизвестный метод';
            die(json_encode($result));
        break;
    }
    
    die(json_encode($result));

    function get_cities(){
        
        $request = array(
            "apiKey" => '',
            "modelName" => "Address",
            "calledMethod" => "getCities",
            "methodProperties" => array(
                "Page" => 1
            )
        );
        
        $response = np_request(json_encode($request));
        if(isset($_GET['selected_city'])) {
            $selected_city = trim(strip_tags(htmlspecialchars($_GET['selected_city'])));
        }
        
        if($response->success){
            $result['success'] = $response->success;
            $result['cities'] = '<option value=""></option>';
            foreach ($response->data as $i=>$city) {
                $result['cities'] .= '<option value="'.htmlspecialchars($city->DescriptionRu).'" data-city_ref="'.$city->Ref.'" '.(!empty($selected_city) && $selected_city == $city->Ref ? 'selected' : '').'>'.htmlspecialchars($city->DescriptionRu).'</option>';
            }
            return $result;
        }
    }
    
    function get_warehouses($cityRef){
    
        $request = array(
            "apiKey" => '',
            "modelName" => "Address",
            "calledMethod" => "getWarehouses",
            "methodProperties" => array(
                "CityRef" => $cityRef,
                "Page" => 1
            )
        );
        
        $response = np_request(json_encode($request));

        if($response->success){
            $result['success'] = $response->success;
            $result['warehouses'] = '<option selected disabled value="">Выберите отделение доставки</option>';
            foreach ($response->data as $i=>$warehouse) {
                $result['warehouses'] .= '<option value="'.htmlspecialchars($warehouse->DescriptionRu).'" data-warehouse_ref="'.$warehouse->Ref.'">'.htmlspecialchars($warehouse->DescriptionRu).'</option>';
            }
            return $result;
        }
    }
    
    function np_request($request){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.novaposhta.ua/v2.0/json/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }