<?php

namespace GeekBrains\LevelTwo\Http\Actions;

use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;

interface ActionInterface
{
    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response;
}