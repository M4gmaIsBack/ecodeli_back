<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommercantController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\PrestataireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnoncePrestataireController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MFAController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\DocumentJustificatifController;
use App\Http\Controllers\ReponseTicketController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\EntrepotController;

Route::post('/create-checkout-session', [StripeController::class, 'createCheckoutSession']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-mobile', [AuthController::class, 'loginMobile']);
Route::post('/register', [AuthController::class, 'register']);



Route::get('/livraisons', [LivraisonController::class, 'index']);
Route::post('/livraisons', [LivraisonController::class, 'store']);

// STATS
Route::get('/stats/users', [StatsController::class, 'userStats']);
Route::get('/stats/distribution', [StatsController::class, 'distribution']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/annonces/prestataire', [AnnoncePrestataireController::class, 'index']);
    Route::post('/annonces/prestataire', [AnnoncePrestataireController::class, 'store']);
    Route::get('/annonces/prestataire/client/{id}', [AnnoncePrestataireController::class, 'getAnnoncesByClientId']);
    Route::get('/annonces/prestataire/prestataire/{id}', [AnnoncePrestataireController::class, 'getPrestationsByPrestataireId']);
    Route::get('/livraisons/livreur/{id}', [LivraisonController::class, 'getLivraisonsByLivreurId']);
    Route::get('/annonces/prestataire/{id}', [AnnoncePrestataireController::class, 'show']);
    Route::put('/annonces/prestataire/{id}', [AnnoncePrestataireController::class, 'update']);
    Route::delete('/annonces/prestataire/{id}', [AnnoncePrestataireController::class, 'destroy']);

    Route::post('/upload-document', [DocumentJustificatifController::class, 'uploadDocument']);
    Route::get('/mes-documents', [DocumentJustificatifController::class, 'mesDocuments']);
    Route::delete('/supprimer-document/{id}', [DocumentJustificatifController::class, 'supprimerDocument']);
    Route::get('livreurs/{id}/documents', [LivreurController::class, 'documents']);
    Route::get('documents/{document}/download', [DocumentJustificatifController::class, 'download']);

    Route::get('/mfa/setup', [MFAController::class, 'setup']);
    Route::post('/mfa/verify', [MFAController::class, 'verify']);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/upload-photo', [AuthController::class, 'uploadPhoto']);

    Route::post('/utilisateurs', [UserController::class, 'store']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::post('/commercants', [CommercantController::class, 'store']);
    Route::post('/livreurs', [LivreurController::class, 'store']);
    Route::post('/prestataires', [PrestataireController::class, 'store']);

    Route::get('/utilisateurs', [UserController::class, 'index']);
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/commercants', [CommercantController::class, 'index']);
    Route::get('/livreurs', [LivreurController::class, 'index']);
    Route::get('/prestataires', [PrestataireController::class, 'index']);

    Route::put('/utilisateurs/{id}', [UserController::class, 'update']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::put('/commercants/{id}', [CommercantController::class, 'update']);
    Route::put('/livreurs/{id}', [LivreurController::class, 'update']);
    Route::put('/prestataires/{id}', [PrestataireController::class, 'update']);

    Route::delete('/utilisateurs/{id}', [UserController::class, 'destroy']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);
    Route::delete('/commercants/{id}', [CommercantController::class, 'destroy']);
    Route::delete('/livreurs/{id}', [LivreurController::class, 'destroy']);
    Route::delete('/prestataires/{id}', [PrestataireController::class, 'destroy']);

    Route::post('/prestations', [PrestationController::class, 'store']);
    Route::get('/prestations', [PrestationController::class, 'index']);
    Route::get('/prestations/client/{id}', [PrestationController::class, 'getPrestationsByClientId']);
    Route::get('/prestations/{id}', [PrestationController::class, 'show']);
    Route::put('/prestations/{id}', [PrestationController::class, 'update']);
    Route::delete('/prestations/{id}', [PrestationController::class, 'destroy']);

    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'getTicketsByClientId']);
    Route::put('/tickets/{id}', [TicketController::class, 'update']);
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);

    Route::post('/paiements', [PaiementController::class, 'store']);
    Route::get('/paiements', [PaiementController::class, 'index']);
    Route::put('/paiements/{id}', [PaiementController::class, 'update']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::post('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead']);

    Route::get('/paiements', [PaiementController::class, 'index']);
    Route::post('/paiements', [PaiementController::class, 'store']);
    Route::put('/paiements/{id}', [PaiementController::class, 'update']);

    Route::get('/prestations/non-payees/{id}', [AnnoncePrestataireController::class, 'getNonPayeesParPrestataire']);
    Route::get('/livraisons/non-payees/{id}', [LivraisonController::class, 'getNonPayeesParLivreur']);

    Route::get('/tickets/{id}/reponses', [ReponseTicketController::class, 'getReponsesByTicketId']);
    Route::post('/tickets/{id}/reponses', [ReponseTicketController::class, 'store']);

    Route::get('/entrepots', [EntrepotController::class, 'index']);
    Route::post('/entrepots', [EntrepotController::class, 'store']);
    Route::put('/entrepots/{entrepot}', [EntrepotController::class, 'update']);
    Route::delete('/entrepots/{entrepot}', [EntrepotController::class, 'delete']);

    Route::post('/livraisons/{livraison}/accept', [LivraisonController::class, 'accept']);
    Route::post('/livraisons/{livraison}/valider', [LivraisonController::class, 'valider']);
    Route::post('/livraisons/{livraison}/valider-partiel', [LivraisonController::class, 'validerPartiel']);
});
