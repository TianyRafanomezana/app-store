//Dans ce fichier fait le pont entre le controller back et le hook : On appel le back et on revoit la réponse
// C'est un peu les ingrédients brut qu'on donne au cuisinier
// Le hook c'est la cuisine ou il y a le  cuisinier

// Il me permet d'attraper le résultat du controller

export async function fetchHabits(){ // On revoie juste la réponse de l'api
        const response = await fetch('/habit-user');
    if (!response.ok) {
        // Si le statut HTTP n'est pas 2xx
        throw new Error(`Erreur lors de la récupération des habitudes: ${response.status}`);
    }

    return response.json();
}


//Attrape la réponse lorsqu'on utilise une méthode POST
export async function addHabit(name: string) {
    const res = await fetch("/habits", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name }),
    });
    return res.json();
}

export async function toggleHabit(id: number) {
    const res = await fetch(`/habits/${id}/toggle`, {
        method: "POST",
    });
    return res.json();
}
