<?php

namespace App\Pages\MyAccountPage\Services;


use Exception;
use App\Modules\Addresses\Resources\AddressResource;
use App\Modules\Addresses\Repository\AddressRepository;
use App\Modules\Orders\Repository\OrderRepository;
use App\Pages\MyAccountPage\Resources\UserInfoResource;
use App\Pages\MyAccountPage\Resources\MyAccountPageOrdersResource;


class MyAccountPageService
{
    private $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }
    public function getUserInformation()
    {
        return UserInfoResource::make(auth()->user());
    }
    public function getUserAddresses()
    {
        return  AddressResource::collection($this->addressRepository->getUserAddresses())->resolve();
    }
    public function getUserOrders()
    {
        return  MyAccountPageOrdersResource::collection((new OrderRepository())->getAllOrders())->resolve();
    }
    public function getUserOrderDetail($id)
    {
        return  MyAccountPageOrdersResource::make((new OrderRepository())->getOrder($id))->resolve();
    }
    public function createAddress($validatedRequest)
    {
        return  AddressResource::make($this->addressRepository->createAddress($validatedRequest))->resolve();
    }

    public function destroyUserAddress($address_id)
    {
        return $this->addressRepository->deleteAddress($address_id);
    }

    public function updateAddress($validatedRequest, $address_id)
    {
        return  $this->addressRepository->updateAddress($validatedRequest, $address_id);
    }
    public function updatePhoneNumber($phoneNumber)
    {
        auth()->user()->update(['phone_number' => intval($phoneNumber)]);
    }
    public function updatePassword($password)
    {
        auth()->user()->update(['password' => $password]);
    }
    public function updateAccountInfo($information)
    {
        $user =  auth()->user()->update($information);

        return UserInfoResource::make($user);
    }
}