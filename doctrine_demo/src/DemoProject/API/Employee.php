<?php


namespace DemoProject\API;

use DemoProject\Entities;
use Doctrine\ORM\EntityManager;


class Employees{

    private static $limit = 20;
    private static EntityManager $entityManager;

    private static function authorize(): bool {
        $entity = self::$entityManager->getRepository(Entities\Auth::class)->findOneBy(
            [
                'login' => $_REQUEST['login'],
                'passw' => $_REQUEST['passw'],
            ]
        );

        if($entity !== null){
            $entity->setLastAuth();
            self::$entityManager->persist($entity);
            self::$entityManager->flush();
			return true;
		}
        throw new \Exception('Authorization failed!');
    }

    private static function getEmployees(): array {
        $employees = self::$entityManager->getRepository(Entities\Employee::class)->findBy(
            [],
            null,
            self::$limit
        );
        $ret = [];
        foreach ($employees as $employee){
            $ret[] = [$employee->getId(), $employee->getFio()];
        }
        self::$entityManager->flush();
        return $ret;
    }

    private static function getOffices(): array {
        $offices = self::$entityManager->getRepository(Entities\Office::class)->findBy(
            [],
            null,
            self::$limit
        );
        $ret = [];
        foreach ($offices as $office){
            $ret[] = [$office->getId(), $office->getName()];
        }
        self::$entityManager->flush();
        return $ret;
    }

    private static function getEmployeesWithOffices(): array {
        $employees = self::$entityManager->getRepository(Entities\Employee::class)->findBy(
            [],
            null,
            self::$limit
        );
        $ret = [];
        foreach ($employees as $employee){
            $office = $employee->getOffice();
            $ret[] =
            [
                $employee->getId(),
                $employee->getFio(),
                $employee->getPhone(),
                $employee->getPost(),
                $office->getName(),
                $office->getAddress(),
            ];
        }
        self::$entityManager->flush();
        return $ret;
    }

    private static function updateEmployee(): int {

        $entity = self::$entityManager->find(Entities\Employee::class, (int)$_REQUEST['id']);

        if ($entity === null) {
            throw new \Exception('Update failed!');
        }

        $entity->setName($_REQUEST['fio']);
        $entity->setPhone((int)$_REQUEST['phone']);
        $entity->setPost($_REQUEST['post']);

        self::$entityManager->persist($entity);
        self::$entityManager->flush();

        return $entity->getId();
    }

    private static function addEmployee(): int {
        $office = self::$entityManager->find(Entities\Office::class, (int)$_REQUEST['office_id']);

        $entity = new Entities\Employee();
        $entity->setName($_REQUEST['fio']);
        $entity->setPhone((int)$_REQUEST['phone']);
        $entity->setPost($_REQUEST['post']);
        $entity->setOffice($office);

        self::$entityManager->persist($entity);
        self::$entityManager->flush();

        return $entity->getId();
    }

    private static function removeEmployee(): bool {
        self::$entityManager->remove(self::$entityManager->find(Entities\Employee::class, (int)$_REQUEST['id']));
        self::$entityManager->flush();
        return true;
    }

    private static function searchEmployees(string $search): array {
        $dql = "SELECT e.id, e.fio FROM " . Entities\Employee::class . " e WHERE e.fio LIKE ?1";
        $query = self::$entityManager->createQuery($dql);
        $query->setParameter(1, '%'.$search.'%');
        $query->setMaxResults(self::$limit);
        return $query->getArrayResult();
	}

	public static function setEntityManager(EntityManager $em){
        self::$entityManager = $em;
    }

    public static function apiMethod(){
        try{
            if (isset(self::$entityManager)){
                throw new \Exception('No Entity manager! Please set EM by method ' . self::class . '::setEntityManager()');
            }

            if(self::authorize()){
                $method = $_REQUEST['method'];
                switch ($method){
                    case 'employees':
                        $data = self::getEmployees();
                        break;
                    case 'offices':
                        $data = self::getOffices();
                        break;
                    case 'employees_whth_offices':
                        $data = self::getEmployeesWithOffices();
                        break;
                    case 'update':
                        $data = self::updateEmployee();
                        break;
                    case 'add':
                        $data = self::addEmployee();
                        break;
                    case 'remove':
                        $data = self::removeEmployee();
                        break;
                    case 'search':
                        $data = self::searchEmployees();
                        break;
                    default :
                        throw new \Exception('No such method!');
                }
                return json_encode([
                    'status' => 'ok',
                    'data' => $data,
                ]);

            }
        }
        catch (\Exception $e){
            return json_encode([
                'status' => 'error',
                'data' => $e->getMessage(),
            ]);
        }
    }
}
