<?php

namespace Ginger\Repository;

use Cl\Test\DoctrineTestCase;
use Ginger\Entity\User;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-07-24 at 21:03:16.
 */
class UserRepositoryTest extends DoctrineTestCase
{

    /**
     * @var UserRepository
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->createEntitySchema('Ginger\Entity\User');
        $this->createEntitySchema('Ginger\Entity\Job');
        $this->createEntitySchema('Ginger\Entity\Permission');
        
        $this->object = $this->getTestEntityManager()->getRepository('Ginger\Entity\User');
        
        $max = new User();
        
        $max->setApiKey('12345api');
        $max->setSecretKey('6789secret');
        $max->setLastname('Mustermann');
        $max->setFirstname('Max');
        $max->setEmail('m.mustermann@company.com');
        $max->setIsAdmin(false);
        
        $this->getTestEntityManager()->persist($max);
        
        $joe = new User();
        $joe->setApiKey('5555api');
        $joe->setSecretKey('6666secret');
        $joe->setLastname('Smith');
        $joe->setFirstname('Joe');
        $joe->setEmail('j.smith@company.com');
        $joe->setIsAdmin(true);
        
        $this->getTestEntityManager()->persist($joe);
        
        $this->getTestEntityManager()->flush();
    }

    /**
     * @covers Ginger\Repository\UserRepository::getUsers
     */
    public function testGetUsers()
    {
        $usersData = $this->object->getUsers();
        
        $check = array(
            array(
                'id' => 1,
                'apiKey' => '12345api',
                'secretKey' => '6789secret',
                'lastname' => 'Mustermann',
                'firstname' => 'Max',
                'email' => 'm.mustermann@company.com',
                'isAdmin' => false
            ),
            array(
                'id' => 2,
                'apiKey' => '5555api',
                'secretKey' => '6666secret',
                'lastname' => 'Smith',
                'firstname' => 'Joe',
                'email' => 'j.smith@company.com',
                'isAdmin' => true
            ),
        );
        
        $this->assertEquals($check, $usersData);
    }

    /**
     * @covers Ginger\Repository\UserRepository::hasUsers
     */
    public function testHasUsers()
    {
        $this->assertTrue($this->object->hasUsers());
    }

    /**
     * @covers Ginger\Repository\UserRepository::loadUser
     */
    public function testLoadUser()
    {
        $userData = $this->object->loadUser(2);
        
        $check = array(
            'id' => 2,
            'apiKey' => '5555api',
            'secretKey' => '6666secret',
            'lastname' => 'Smith',
            'firstname' => 'Joe',
            'email' => 'j.smith@company.com',
            'isAdmin' => true
        );
        
        $this->assertEquals($check, $userData);
    }

    /**
     * @covers Ginger\Repository\UserRepository::loadUserByApiKey
     */
    public function testLoadUserByApiKey()
    {
        $userData = $this->object->loadUserByApiKey('5555api');
        
        $check = array(
            'id' => 2,
            'apiKey' => '5555api',
            'secretKey' => '6666secret',
            'lastname' => 'Smith',
            'firstname' => 'Joe',
            'email' => 'j.smith@company.com',
            'isAdmin' => true
        );
        
        $this->assertEquals($check, $userData);
    }

    /**
     * @covers Ginger\Repository\UserRepository::saveUser
     */
    public function testSaveUser()
    {
        //create a new user
        $id = $this->object->saveUser(array(
            'apiKey' => '7777api',
            'secretKey' => '8888secret',
            'lastname' => 'Doe',
            'firstname' => 'John',
            'email' => 'j.doe@company.com',
            'isAdmin' => false
        ));
        
        $this->assertEquals(3, $id);
        
        $john = $this->object->loadUser($id);
        
        $check = array(
            'id' => 3,
            'apiKey' => '7777api',
            'secretKey' => '8888secret',
            'lastname' => 'Doe',
            'firstname' => 'John',
            'email' => 'j.doe@company.com',
            'isAdmin' => false
        );
        
        $this->assertEquals($check, $john);
        
        //Update user data (rename joe to john)
        $john['id'] = 2;
        $john['apiKey'] = '5555api';
        $john['secretKey'] = '6666secret';
        
        $id = $this->object->saveUser($john);
        
        $this->assertEquals(2, $id);
        
        $check = array(
            'id' => 2,
            'apiKey' => '5555api',
            'secretKey' => '6666secret',
            'lastname' => 'Doe',
            'firstname' => 'John',
            'email' => 'j.doe@company.com',
            'isAdmin' => false
        );
        
        $joeRenamedToJohn = $this->object->loadUser(2);
        
        $this->assertEquals($check, $joeRenamedToJohn);
    }

    /**
     * @covers Ginger\Repository\UserRepository::deleteUser
     */
    public function testDeleteUser()
    {
        $this->object->deleteUser(1);
        
        $this->assertNull($this->object->loadUser(1));
    }

}