<?php


/**
 * @OA\Get(
 * path="/message/{username}",
 * tags={"messages"},
 * summary="Retrieve all messages for a specific user.",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="username",
 * in="path",
 * required=true,
 * description="The username of the customer.",
 * @OA\Schema(
 * type="string",
 * example="Nejra123"
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns a list of messages.",
 
 * ))
 */
Flight::route("GET /message/@username", function ($username) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::messageService()->getMessagesByUsername($username));
});

?>