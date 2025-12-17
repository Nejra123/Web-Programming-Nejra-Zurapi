<?php

/**
 * @OA\Info(
 *   title="API",
 *   description="Quart Market API",
 *   version="1.0",
 *   @OA\Contact(
 *     email="quartmarket@gmail.com",
 *     name="Quart Market"
 *   )
 * ),
 * @OA\Server(
 *     url=LOCALSERVER,
 *     description="API server"
 * ),
 * @OA\Server(
 *     url="https://seal-app-nyueq.ondigitalocean.app/backend",
 *     description="API server"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */