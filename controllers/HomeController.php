<?php

include_once __DIR__ . '/../database/dbConnection.php';


class HomeController
{

    private $dbConnection;

    public function __construct(PDO $conn)
    {
        $this->dbConnection = $conn;
    }

    public function index(): array
    {
        $sql = 'SELECT p.name AS productName, c.name as customerName, p.price FROM sales s 
    INNER JOIN products p ON p.id = s.product_id INNER JOIN customers c ON c.id = s.customer_id;';
        $statement = $this->dbConnection->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(): string
    {
        $data = file_get_contents("Code_Challenge_Sales.json");
        $data = json_decode($data, true);
        $error = 0;
        foreach ($data as $value) {
            $customerResponse = $this->createCustomer($value);
            $productResponse = $this->createProduct($value);
            if(!$customerResponse['error'] && !$productResponse['error']) {
                $this->createSales(
                    ['customer_id' => $customerResponse['data'], 'product_id' => $productResponse['data']]
                );
            } else {
                $error++;
            }
        }
        if($error > 0) {
            return "Something went wrong";
        }
        return 'DATA SAVED SUCCESSFULLY';
    }


    public function createCustomer(array $data): array
    {
        $result = ['error' => true, 'message' => ""];
        try {
            $sql = "INSERT INTO customers (name, email) VALUES (?,?)";
            $this->dbConnection->prepare($sql)->execute([$data['customer_name'], $data['customer_mail']]);
            $lastInsertId = $this->dbConnection->lastInsertId();
            $result = ['error' => false, 'data' => $lastInsertId];
        } catch (\Exception $exception) {
            $result['message'] = sprintf("Error occurred: %s", $exception->getMessage());
        }
        return $result;
    }

    public function createProduct(array $data): array
    {
        $result = ['error' => true, 'message' => ""];
        try {
            $sql = "INSERT INTO products (name, price) VALUES (?,?)";
            $this->dbConnection->prepare($sql)->execute([$data['product_name'], $data['product_price']]);
            $lastInsertId = $this->dbConnection->lastInsertId();
            $result = ['error' => false, 'data' => $lastInsertId];
        } catch (\Exception $exception) {
            $result['message'] = sprintf("Error occurred: %s", $exception->getMessage());
        }
        return $result;
    }

    public function createSales(array $data): array
    {
        $result = ['error' => true, 'message' => ""];
        try {
            $sql = "INSERT INTO sales (customer_id, product_id) VALUES (?,?)";
            $this->dbConnection->prepare($sql)->execute([$data['customer_id'], $data['product_id']]);
            $lastInsertId = $this->dbConnection->lastInsertId();
            $result = ['error' => false, 'data' => $lastInsertId];
        } catch (\Exception $exception) {
            $result['message'] = sprintf("Error occurred: %s", $exception->getMessage());
        }
        return $result;
    }

    public function filter(?array $filter = []): array
    {
        $sql = 'SELECT p.name AS productName, c.name as customerName, p.price FROM sales s 
    INNER JOIN products p ON p.id = s.product_id INNER JOIN customers c ON c.id = s.customer_id';
        if (!empty($filter)) {
            $where = ' where';
            $execute = [];
            if (!empty($filter['customer'])) {
                $where .= " c.name = ?";
                $execute[] = $filter['customer'];
            }

            if (!empty($filter['product'])) {
                $where .= " p.product = ?";
                $execute[] = $filter['product'];
            }

            if (!empty($filter['price'])) {
                $where .= " p.price = ?";
                $execute[] = $filter['price'];
            }
            if($execute) {
                $sql .= $where;
                $statement = $this->dbConnection->prepare($sql);
                $statement->execute($execute);
            }else {
                $statement = $this->dbConnection->query($sql);
            }
        } else {
            $statement = $this->dbConnection->query($sql);
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}