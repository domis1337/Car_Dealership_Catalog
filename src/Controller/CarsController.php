<?php

namespace App\Controller;

use App\Entity\Cars;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CarsController extends AbstractController
{
    //#[Route('/', name: 'list_cars', methods={"GET"})]
	
	/**
     *
     * @Route("/", name="list_cars", methods={"GET"})
     */
    public function listCars(Request $request): Response
    {	
		$searchValue = $request->query->get('sfield');
		$selectedValue = $request->query->get('svalue');
		
		$column;
		
		switch ($selectedValue) {
			case 1:
				$column = 'brand';
				break;
			case 2:
				$column = 'model';
				break;
			case 3:
				$column = 'fuel_type';
				break;
			case 4:
				$column = 'fuel_tank_capacity';
				break;
			case 5:
				$column = 'max_speed';
				break;
			case 6:
				$column = 'price';
				break;
			case 7:
				$column = 'color';
				break;
			default:
				$column = 'id';
						
		}
		
		$cars = $this->getDoctrine()
			->getRepository(Cars::class)
			->findByBrandField($searchValue, $column);
			
        return $this->render('cars/index.html.twig', [
            'cars' => $cars, 'searchValue' => $searchValue,
        ]);
    }
	
	/**
     *
     * @Route("/viewcar", name="view_car", methods={"GET"})
     */
	public function viewCar(Request $request): Response
    {
        $searchValue = $request->query->get('vcarid');
	
			$car = $this->getDoctrine()
			->getRepository(Cars::class)
			->findOneByIdField($searchValue);
	
        return $this->render('cars/viewcar.html.twig', [
            'car' => $car,
        ]);
    }
	
	#[Route('/createcar', name: 'create_car_form')]
	public function createCarForm(): Response
    {
        return $this->render('cars/createcar.html.twig', [
            'controller_name' => 'CreateCarForm',
        ]);
    }
	
	/**
     *
     * @Route("/createdcar", name="created_car", methods={"GET"})
     */
	public function createCar(ValidatorInterface $validator, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
		
		$brand = $request->query->get('abrand');
		$model = $request->query->get('amodel');
		$ftype;
		switch ($request->query->get('aftype')) {
			case 1:
				$ftype = 'Diesel';
				break;
			case 2:
				$ftype = 'Petrol';
				break;
		}
		$ftcapacity = $request->query->get('aftcapacity');
		$mspeed = $request->query->get('amspeed');
		$price = $request->query->get('aprice');
		$color = $request->query->get('acolor');
		$description = $request->query->get('adescription');

        $car = new Cars();
        $car->setBrand($brand);
		$car->setModel($model);
		$car->setFuelType($ftype);
		$car->setFuelTankCapacity($ftcapacity);
		$car->setMaxSpeed($mspeed);
		$car->setPrice($price);
		$car->setColor($color);
		$car->setDescription($description);
		
		$errors = $validator->validate($car);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
        

        // tell Doctrine you want to (eventually) save the Car (no queries yet)
        $entityManager->persist($car);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
		
		return $this->render('cars/createdcar.html.twig', [
            'car' => $car->getId(), 'brand' => $car->getBrand(),
        ]);
    }
	
	/**
     *
     * @Route("/editcar", name="edit_car_form", methods={"GET"})
     */
	public function editCarForm(Request $request): Response
    {		
			$searchValue = $request->query->get('ecarid');
	
			$car = $this->getDoctrine()
			->getRepository(Cars::class)
			->findOneByIdField($searchValue);
	
        return $this->render('cars/editcar.html.twig', [
            'car' => $car,
        ]);
    }
	
	/**
     *
     * @Route("/editedcar", name="edited_car", methods={"GET"})
     */
	public function editCar(ValidatorInterface $validator, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
		$id = $searchValue = $request->query->get('eid');
        $car = $entityManager->getRepository(Cars::class)->find($id);
		
        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id '.$id
            );
        }
		
		$brand = $request->query->get('ebrand');
		$model = $request->query->get('emodel');
		$ftype;
		switch ($request->query->get('eftype')) {
			case 1:
				$ftype = 'Diesel';
				break;
			case 2:
				$ftype = 'Petrol';
				break;
		}
		$ftcapacity = $request->query->get('eftcapacity');
		$mspeed = $request->query->get('emspeed');
		$price = $request->query->get('eprice');
		$color = $request->query->get('ecolor');
		$description = $request->query->get('edescription');

        $car->setBrand($brand);
		$car->setModel($model);
		$car->setFuelType($ftype);
		$car->setFuelTankCapacity($ftcapacity);
		$car->setMaxSpeed($mspeed);
		$car->setPrice($price);
		$car->setColor($color);
		$car->setDescription($description);
		
		$errors = $validator->validate($car);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
		
        $entityManager->flush();

        return $this->render('cars/editedcar.html.twig', [
            'car' => $car->getId(), 'brand' => $car->getBrand(),
        ]);
    }
	
	/**
     *
     * @Route("/deletecar", name="delete_car", methods={"GET"})
     */
	public function deleteCar(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
		$id = $searchValue = $request->query->get('dcarid');
        $car = $entityManager->getRepository(Cars::class)->find($id);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id '.$id
            );
        }
		
		$entityManager->remove($car);
		$entityManager->flush();
		
		return $this->redirectToRoute('list_cars', [
            
        ]);
    }
	
	/**
     *
     * @Route("/updatecarprice", name="update_car_price", methods={"GET"})
     */
	public function updateCarPrice(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
		$id = $searchValue = $request->query->get('ocarid');
        $car = $entityManager->getRepository(Cars::class)->find($id);
		
		$price = $request->query->get('oprice');

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id '.$id
            );
        }
		
		$car->setPrice($price);
		$entityManager->flush();
		
		return $this->redirectToRoute('list_cars', [
            
        ]);
    }
}
