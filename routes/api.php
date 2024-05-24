<?php

use App\Http\Middleware\AuthGuard;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return response()->json(
        ['Status' => 'Ok'],
        Response::HTTP_OK
    );
});

Route::prefix('/auth')
    ->group(app_path('Features/Auth/Routes/authRouter.php'));

Route::middleware(AuthGuard::class)->group(function () {
    Route::prefix('/admin-users')
        ->group(app_path('Features/User/AdminUsers/Routes/adminUsersRouter.php'));

    Route::prefix('/profiles')
        ->group(app_path('Features/User/Profiles/Routes/profilesRouter.php'));

    Route::prefix('/team-users')
        ->group(app_path('Features/User/TeamUsers/Routes/teamUsersRouter.php'));

    Route::prefix('/projects')
        ->group(app_path('Features/Project/Projects/Routes/projectsRouter.php'));

    Route::prefix('/notes')
        ->group(app_path('Features/General/Notes/Routes/notesRouter.php'));

    Route::prefix('/colors')
        ->group(app_path('Features/General/Colors/Routes/colorsRouter.php'));

    Route::prefix('/icons')
        ->group(app_path('Features/General/Icons/Routes/iconsRouter.php'));

    Route::prefix('/sections')
        ->group(app_path('Features/Project/Sections/Routes/sectionsRouter.php'));

    Route::prefix('/tags')
        ->group(app_path('Features/General/Tags/Routes/tagsRouter.php'));

    Route::prefix('/cards')
        ->group(app_path('Features/Project/Cards/Routes/cardsRouter.php'));
});
