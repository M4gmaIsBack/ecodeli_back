<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentJustificatif;
use Illuminate\Support\Facades\Storage;

class DocumentJustificatifController extends Controller
{
    public function uploadDocument(Request $request)
    {
        try {
            if (!auth()->check()) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }
    
            $request->validate([
                'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'type' => 'nullable|string|max:255',
            ]);
    
            $file = $request->file('document');
            $path = $file->store('documents', 'public');
    
            $document = DocumentJustificatif::create([
                'user_id' => auth()->id(),
                'filename' => $file->getClientOriginalName(),
                'chemin' => $path,
                'type' => $request->type,
                'taille' => $file->getSize(),
            ]);
    
            return response()->json([
                'message' => 'Fichier enregistré',
                'document' => $document,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur upload document : ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function mesDocuments()
    {
        $documents = auth()->user()->documentsJustificatifs()->get();
    
        return response()->json([
            'documents' => $documents
        ]);
    }

    public function supprimerDocument($id)
    {
        $document = DocumentJustificatif::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$document) {
            return response()->json(['message' => 'Document introuvable'], 404);
        }

        Storage::disk('public')->delete($document->chemin);
        $document->delete();

        return response()->json(['message' => 'Document supprimé']);
    }

    public function download(DocumentJustificatif $document)
    {
        return Storage::disk('public')->download($document->chemin, $document->filename);
    }

    
    public function forUser($userId)
    {
        $docs = DocumentJustificatif::where('user_id', $userId)->get();
        return response()->json([
        'documents' => $docs->map(fn($d) => [
            'id'         => $d->id,
            'filename'   => $d->filename,
            'chemin'     => $d->chemin,
            'created_at' => $d->created_at,
        ]),
        ]);
    }
}
