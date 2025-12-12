<?php

/**
 * @OA\Get(
 * path="/messages/{username}",
 * tags={"messages"},
 * summary="Retrieve all messages for a specific user.",
 * security={{"ApiKey": {}}},
 * @OA\Parameter(
 *     name="username",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="string", example="Nejra123")
 * ),
 * @OA\Response(response=200, description="Returns a list of messages.")
 * )
 */
Flight::route("GET /messages/@username", function ($username) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::messageService()->getMessagesByUsername($username));
});

/**
 * @OA\Post(
 * path="/messages",
 * tags={"messages"},
 * summary="Create a new message",
 * security={{"ApiKey": {}}},
 * @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *         required={"username", "content", "customer_id"},
 *         @OA\Property(property="username", type="string", example="john_doe"),
 *         @OA\Property(property="content", type="string", example="Hello, I have a question..."),
 *        
 *     )
 * ),
 * @OA\Response(response=200, description="Message sent successfully")
 * )
 */
Flight::route("POST /messages", function () {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    $data = Flight::request()->data->getData();
    
    // Add validation
    if (empty($data['username']) || empty($data['content']) || empty($data['customer_id'])) {
        Flight::json(['success' => false, 'error' => 'Missing required fields'], 400);
        return;
    }
    
    $result = Flight::messageService()->create($data);
    Flight::json(['success' => true, 'data' => $result]);
});

?>