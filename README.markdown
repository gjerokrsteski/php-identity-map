Building an Identity Map in PHP
===============================

Sample application used for training.

This example code is no production code and should be used for training
purposes only.

This example code requires:
---------------------------
* PDO a lightweight, consistent interface for accessing databases in PHP.
* PHPUnit a unit testing framework for PHP projects.

This example code implements:
-----------------------
* Active-record pattern
* Identity-map pattern

Why identity mapping?
---------------------
By using active-record pattern without an identity map, you can easily run
into problems because you may have more than one object that references
the same domain entity.

Active-record without identity map
----------------------------------

      $userMapper = new UserMapper($pdo);

      $user1 = $userMapper->findById(1); // new object created
      $user2 = $userMapper->findById(1); // new object created

      echo $user1; // joe123
      echo $user2; // joe123

      $user1->setNickname('bob78');

      echo $user1; // bob78
      echo $user2; // joe123 -> ?!?

Active-record with identity map
----------------------------------
The identity map solves this problem by acting as a registry for all
loaded domain instances.

      $userMapper = new UserMapper($pdo);

      $user1 = $userMapper->findById(1); // new object created
      $user2 = $userMapper->findById(1); // returns same object

      echo $user1; // joe123
      echo $user2; // joe123

      $user1->setNickname('bob78');

      echo $user1; // bob78
      echo $user2; // bob78 -> yes, much better

By using an identity map you can be confident that your domain entity is
shared throughout your application for the duration of the request.

Note that using an identity map is not the same as adding a cache layer
to your mappers. Although caching is useful and encouraged, it can still
produce duplicate objects for the same domain entity.