console.log("Autor webu: Jan Chaloupka")

class UserSettings {
    constructor() {
        this.initRoleChange();
        this.initBlocking();
        this.initUnblocking();
    }

    // Změna role uživatele
    initRoleChange() {
        document.querySelectorAll(".role-select").forEach(select => {
            select.addEventListener("change", async (e) => {
                const tr = e.target.closest("tr");
                const id = tr.dataset.id;
                const newRole = e.target.value;

                try {
                    const res = await fetch(`app/api/users.php/${id}`, {
                        method: "PUT",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ roles_id: parseInt(newRole) })
                    });

                    const data = await res.json();

                    if (data.success) {
                        alert("✅ Role změněna");
                        location.reload(); // refresh po úspěchu
                    } else {
                        alert("❌ " + (data.error || "Nepodařilo se změnit roli"));
                    }
                } catch (err) {
                    console.error("Chyba při změně role:", err);
                }

            });
        });
    }

    // 🔴 Blokování uživatele
    initBlocking() {
        document.querySelectorAll(".block-btn").forEach(btn => {
            btn.addEventListener("click", async e => {
                const tr = e.target.closest("tr");
                const id = tr.dataset.id;
                if (!confirm("Opravdu chceš uživatele zablokovat?")) return;

                try {
                    const res = await fetch(`app/api/users.php/${id}`, {
                        method: "DELETE"
                    });

                    const data = await res.json();
                    if (data.success) {
                        alert("✅ Uživatel zablokován");
                        location.reload();
                    } else {
                        alert("❌ " + (data.error || "Nepodařilo se zablokovat"));
                    }
                } catch (err) {
                    console.error("Chyba při blokování:", err);
                }
            });
        });
    }

    // Odblokování uživatele
    initUnblocking() {
        document.querySelectorAll(".unblock-btn").forEach(btn => {
            btn.addEventListener("click", async (e) => {
                const tr = e.target.closest("tr");
                const id = tr.dataset.id;

                try {
                    const res = await fetch(`app/api/users.php/${id}`, {
                        method: "PATCH",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ blocked: 0 })
                    });

                    const data = await res.json();
                    if (data.success) {
                        tr.querySelector(".status-cell").innerHTML = `
                            <span class="badge bg-success">Aktivní</span>
                            <button class="btn btn-sm btn-outline-danger block-btn mt-1">Zakázat</button>
                        `;
                        alert("✅ Uživatel odblokován");
                        location.reload(); // refresh po úspěchu
                    } else {
                        alert("❌ " + (data.error || "Nepodařilo se odblokovat"));
                    }
                } catch (err) {
                    console.error("Chyba při odblokování:", err);
                }
            });
        });
    }
}

// 🔥 Automatická inicializace jen na stránce userSettings
document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector("table")?.classList.contains("user-settings-table")) {
        new UserSettings();
    }
});
