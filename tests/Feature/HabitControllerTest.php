<?php

namespace Tests\Feature; // Déclare le namespace pour les tests de fonctionnalités.

use App\Models\User; // Importe le modèle User pour créer des utilisateurs.
use App\Models\Habit; // Importe le modèle Habit pour interagir avec les habitudes.
use Illuminate\Foundation\Testing\RefreshDatabase; // Trait pour rafraîchir la base de données après chaque test (assure l'isolation).
use Tests\TestCase; // Classe de base pour tous les tests.

class HabitControllerTest extends TestCase
{
    use RefreshDatabase; // Utilise le trait pour réinitialiser la base de données avant chaque méthode de test.

    /**
     * Test the habit store (creation) endpoint.
     */
    public function test_store_habit()
    {
        // **Préparation :** Crée un utilisateur de test qui sera utilisé pour l'authentification.
        // L'ID, le nom et l'email sont spécifiés pour un contexte clair.
        $user = User::factory()->create([
            'id' => 6,
            'name' => 'Jean',
            'email' => 'jean@gmail.com',
        ]);

        // **Action :** Agit comme l'utilisateur créé ($user) et envoie une requête POST JSON
        // au point de terminaison '/habits' avec les données pour créer une nouvelle habitude.
        $response = $this->actingAs($user)->postJson('/habits', [
            'name' => 'Drink water', // Nom de l'habitude à créer.
        ]);

        // **Assertion de la Réponse :** Vérifie que la requête a réussi avec un code de statut **201 (Created)**.
        // Vérifie également que la réponse JSON contient l'habitude créée.
        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Drink water',
            ]);

        // **Assertion de la Base de Données :** Vérifie que l'habitude a bien été enregistrée dans la table 'habits'
        // avec le nom correct et qu'elle est liée à l'ID de l'utilisateur.
        $this->assertDatabaseHas('habits', [
            'name' => 'Drink water',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test the habit index (listing) endpoint.
     */
    public function test_index_habits()
    {
        // **Préparation :** Récupère l'utilisateur avec l'ID 1 s'il existe (provenant potentiellement du test précédent si RefreshDatabase n'était pas utilisé),
        // sinon, il le crée. Dans le cas où `RefreshDatabase` est utilisé, il le crée toujours.
        $user = User::find(1) ?: User::factory()->create([
            'id' => 1,
            'name' => 'Coucou',
            'email' => 'coucou@gmail.com',
        ]);

        // **Préparation :** Crée une habitude dans la base de données, associée à l'utilisateur de test.
        $habit = Habit::create([
            'name' => 'Morning Run',
            'user_id' => $user->id,
        ]);

        // **Action :** Agit comme l'utilisateur créé ($user) et envoie une requête GET JSON
        // au point de terminaison '/habits' pour récupérer la liste des habitudes.
        $response = $this->actingAs($user)->getJson('dashboard');

        // **Assertion de la Réponse :** Vérifie que la requête a réussi avec un code de statut **200 (OK)**.
        // Vérifie également que la réponse JSON contient l'habitude 'Morning Run', prouvant qu'elle a été listée.
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Morning Run']);
    }

    /**
     * Test the habit_user storeOrUpdate endpoint - CREATE (INSERT) scenario.
     * 
     * Ce test vérifie que updateOrInsert crée une NOUVELLE entrée quand aucune ligne
     * n'existe avec la combinaison user_id/habit_id/date.
     */
    public function test_store_or_update_creates_new_entry()
    {
        // **Préparation :** Crée un utilisateur et une habitude pour le test
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $habit = Habit::create([
            'name' => 'Drink Water',
            'user_id' => $user->id,
        ]);

        // **Action :** Envoie une requête POST pour créer une nouvelle entrée dans habit_user
        // C'est le PREMIER appel pour cette combinaison user/habit/date → INSERT
        $response = $this->actingAs($user)->postJson('/habit-user/store-or-update', [
            'habit_id' => $habit->id,
            'date' => '2024-01-15',
            'checked' => true,
        ]);

        // **Assertion de la Réponse :** Vérifie que la requête a réussi avec un code de statut **200 (OK)**
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // **Assertion de la Base de Données :** Vérifie qu'une NOUVELLE ligne a été créée
        // avec les bonnes valeurs (checked=true, created_at et updated_at définis)
        $this->assertDatabaseHas('habit_user', [
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'date' => '2024-01-15',
            'checked' => true,
        ]);
    }

    /**
     * Test the habit_user storeOrUpdate endpoint - UPDATE scenario.
     * 
     * Ce test vérifie que updateOrInsert MET À JOUR une entrée existante quand une ligne
     * existe déjà avec la combinaison user_id/habit_id/date.
     */
    public function test_store_or_update_updates_existing_entry()
    {
        // **Préparation :** Crée un utilisateur et une habitude
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $habit = Habit::create([
            'name' => 'Morning Run',
            'user_id' => $user->id,
        ]);

        // **Préparation :** Crée une entrée existante dans habit_user (checked=false)
        \DB::table('habit_user')->insert([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'date' => '2024-01-15',
            'checked' => false,
            'created_at' => now()->subDay(), // Créée hier
            'updated_at' => now()->subDay(), // Mise à jour hier
        ]);

        // **Action :** Envoie une requête POST pour mettre à jour l'entrée existante
        // C'est le DEUXIÈME appel pour cette combinaison user/habit/date → UPDATE
        $response = $this->actingAs($user)->postJson('/habit-user/store-or-update', [
            'habit_id' => $habit->id,
            'date' => '2024-01-15',
            'checked' => true, // Change checked de false à true
        ]);

        // **Assertion de la Réponse :** Vérifie que la requête a réussi
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // **Assertion de la Base de Données :** Vérifie que la ligne a été MISE À JOUR
        // checked est maintenant true, et updated_at a été mis à jour
        $this->assertDatabaseHas('habit_user', [
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'date' => '2024-01-15',
            'checked' => true, // Vérifie que checked a été mis à jour
        ]);

        // **Assertion supplémentaire :** Vérifie qu'il n'y a qu'UNE SEULE entrée
        // (pas de duplication grâce à updateOrInsert)
        $this->assertDatabaseCount('habit_user', 1);
    }

    /**
     * Test the habit_user show endpoint - when entry exists.
     * 
     * Ce test vérifie que la méthode show retourne correctement l'état checked
     * quand une entrée existe dans habit_user.
     */
    public function test_show_returns_checked_when_entry_exists()
    {
        // **Préparation :** Crée un utilisateur et une habitude
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $habit = Habit::create([
            'name' => 'Read Book',
            'user_id' => $user->id,
        ]);

        // **Préparation :** Crée une entrée dans habit_user
        \DB::table('habit_user')->insert([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'date' => '2024-01-20',
            'checked' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // **Action :** Envoie une requête GET pour récupérer l'état de l'habitude
        $response = $this->actingAs($user)->getJson('/habit-user/show?habit_id='.$habit->id.'&date=2024-01-20');

        // **Assertion de la Réponse :** Vérifie que la réponse contient checked=true
        $response->assertStatus(200)
            ->assertJson(['checked' => true]);
    }

    /**
     * Test the habit_user show endpoint - when entry does not exist.
     * 
     * Ce test vérifie que la méthode show retourne checked=false par défaut
     * quand aucune entrée n'existe dans habit_user.
     */
    public function test_show_returns_false_when_entry_does_not_exist()
    {
        // **Préparation :** Crée un utilisateur et une habitude
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $habit = Habit::create([
            'name' => 'Meditate',
            'user_id' => $user->id,
        ]);

        // **Action :** Envoie une requête GET pour une date qui n'a pas d'entrée
        $response = $this->actingAs($user)->getJson('/habit-user/show?habit_id='.$habit->id.'&date=2024-01-25');

        // **Assertion de la Réponse :** Vérifie que la réponse retourne checked=false par défaut
        $response->assertStatus(200)
            ->assertJson(['checked' => false]);
    }

    /**
     * Test the habit_user history endpoint.
     * 
     * Ce test vérifie que la méthode history retourne toutes les entrées
     * pour une habitude donnée, triées par date.
     */
    public function test_history_returns_all_entries_sorted_by_date()
    {
        // **Préparation :** Crée un utilisateur et une habitude
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $habit = Habit::create([
            'name' => 'Exercise',
            'user_id' => $user->id,
        ]);

        // **Préparation :** Crée plusieurs entrées dans habit_user pour différentes dates
        \DB::table('habit_user')->insert([
            [
                'user_id' => $user->id,
                'habit_id' => $habit->id,
                'date' => '2024-01-20',
                'checked' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'habit_id' => $habit->id,
                'date' => '2024-01-18',
                'checked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'habit_id' => $habit->id,
                'date' => '2024-01-22',
                'checked' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // **Action :** Envoie une requête GET pour récupérer l'historique complet
        $response = $this->actingAs($user)->getJson('/habit-user/history?habit_id='.$habit->id);

        // **Assertion de la Réponse :** Vérifie que la réponse contient 3 entrées
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertCount(3, $responseData);

        // **Assertion supplémentaire :** Vérifie que les dates sont triées (du plus ancien au plus récent)
        $dates = array_column($responseData, 'date');
        $this->assertEquals(['2024-01-18', '2024-01-20', '2024-01-22'], $dates);
    }

    /**
     * Test the habit_user storeOrUpdate endpoint - validation errors.
     * 
     * Ce test vérifie que la validation fonctionne correctement
     * et retourne des erreurs appropriées.
     */
    public function test_store_or_update_validation_errors()
    {
        // **Préparation :** Crée un utilisateur
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // **Action :** Envoie une requête POST avec des données invalides
        $response = $this->actingAs($user)->postJson('/habit-user/store-or-update', [
            // habit_id manquant
            'date' => 'invalid-date', // Date invalide
            'checked' => 'not-a-boolean', // Boolean invalide
        ]);

        // **Assertion de la Réponse :** Vérifie que la validation échoue avec un code 422
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['habit_id', 'date', 'checked']);
    }
}