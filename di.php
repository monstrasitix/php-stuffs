<?php


class AnotherRepository {
    private static $word = 'says';
    
    public function boom($name) {
        echo $name . ' ' . self::$word . ' BOOM!';
    }
    
    public function baam($name) {
        echo $name . ' ' . self::$word . ' BAAM!';
    }
}


class UserRepository {
    private $anotherRepository;
    
    public function __construct(
        AnotherRepository $anotherRepository
    ) {
        $this->anotherRepository = $anotherRepository;
    }
    
    public function something() {
        $this->anotherRepository->boom('UserRepository');
    }
}


class AuthRepository {
    private $anotherRepository;
    
    public function __construct(
        AnotherRepository $anotherRepository
    ) {
        $this->anotherRepository = $anotherRepository;
    }
    
    public function something() {
        $this->anotherRepository->baam('UserRepository');
    }
}


class UserController {
    private $userRepository;
    private $authRepository;
    
    
    public function __construct(
        UserRepository $userRepository,
        AuthRepository $authRepository
    ) {
        $this->userRepository = $userRepository;
        $this->authRepository = $authRepository;
    }
    
    
    public function initialize() {
        $this->userRepository->something();
        $this->authRepository->something();
    }
}


function initialize_arguments($class_name) {
    $classes = [];
    $reflection = new ReflectionClass($class_name);
    
    $constructor = $reflection->getConstructor();
    
    if ($constructor === NULL) {
        return new $class_name();
    }
        
    $parameters = $constructor->getParameters();

    foreach ($parameters as $parameter) {
        $classes[] = initialize_arguments($parameter->name);
    }
    
    return $reflection->newInstanceArgs($classes);
}


initialize_arguments('UserController')->initialize();
