<?php
Flight::route("GET /message/@username", function ($username) {
    Flight::json(Flight::messageService()->getMessagesByUsername($username));
});


?>