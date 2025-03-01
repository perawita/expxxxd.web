<?php
class APIClient {
    private $urlBackend = 'http://api-cxxxxxcxxn.vercel.app/api/v1';
    private $headers = [
        "x-api-key: df7afd26",
        "x-user-id: 407390",
        "Content-Type: application/json"
    ];

    private function fetchData($url, $method, $body = null) {
        $ch = curl_init($this->urlBackend . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 

        if ($body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return json_encode(["error" => "cURL Error: $error"]);
        }

        if ($httpCode >= 400) {
            return json_encode(["error" => "HTTP Error: $httpCode", "response" => $response]);
        }

        return $response;
    }

    public function getProperti() {
        return $this->fetchData('/properti/akrab', 'GET');
    }

    public function getProduk() {
        return $this->fetchData('/akrab/otomatis/all', 'GET');
    }

    public function getProdukBy($id) {
        return $this->fetchData("/akrab/otomatis/$id", 'GET');
    }

    public function purchase($productId, $customerNumber) {
        $body = [
            "product-id" => $productId,
            "customer-no" => $customerNumber
        ];
        return $this->fetchData('/akrab/otomatis/purchase', 'POST', $body);
    }

    public function addProduct($data) {
        return $this->fetchData('/akrab/otomatis/add', 'POST', $data);
    }

    public function updateProduct($id, $data) {
        return $this->fetchData("/akrab/otomatis/edit/$id", 'PUT', $data);
    }

    public function deleteProduct($id) {
        return $this->fetchData("/akrab/otomatis/delete/$id", 'DELETE');
    }
}
?>
