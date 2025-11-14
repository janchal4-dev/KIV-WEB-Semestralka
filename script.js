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


// na psaní recenzí
class ReviewHandler {
    constructor() {
        this.initEditor();
        this.initStars();
        this.initSubmit();
    }

    initEditor() {
        if (document.getElementById("reviewComment")) {
            CKEDITOR.replace("reviewComment");
        }
    }

    initStars() {
        document.querySelectorAll(".rating").forEach(rating => {
            const field = rating.dataset.field;
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement("i");
                star.className = "bi bi-star";
                star.dataset.value = i;
                star.addEventListener("click", () => this.setRating(rating, i));
                rating.appendChild(star);
            }
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.id = field;
            hidden.value = 0;
            rating.appendChild(hidden);
        });
    }

    setRating(container, value) {
        const stars = container.querySelectorAll("i");
        stars.forEach((s, idx) => {
            s.className = idx < value ? "bi bi-star-fill text-warning" : "bi bi-star";
        });
        container.querySelector("input").value = value;
    }

    initSubmit() {
        const form = document.getElementById("reviewForm");
        if (!form) return;

        form.addEventListener("submit", async e => {
            e.preventDefault();

            const body = {
                post_id: document.getElementById("post_id").value,
                rev_quality: document.getElementById("rev_quality").value,
                rev_language: document.getElementById("rev_language").value,
                rev_originality: document.getElementById("rev_originality").value,
                comment: CKEDITOR.instances.reviewComment.getData()
            };

            try {
                const res = await fetch("app/api/reviews.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(body)
                });
                const data = await res.json();

                if (data.success) {
                    alert("✅ Recenze uložena!");
                    location.href = "index.php?page=articles";
                } else {
                    alert("❌ " + (data.error || "Chyba při ukládání recenze."));
                }
            } catch (err) {
                console.error("Chyba při odeslání recenze:", err);
            }
        });
    }
}

// Automatická inicializace
document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".review-form")) {
        new ReviewHandler();
    }
});



document.addEventListener("DOMContentLoaded", () => {

    // ============================
    // 🔥 1) USER SETTINGS (už běží už ok)
    // ============================
    if (document.querySelector("table")?.classList.contains("user-settings-table")) {
        new UserSettings();
    }

    // ============================
    // 🔥 2) REVIEW EDITOR
    // ============================
    if (document.querySelector(".review-form")) {
        new ReviewHandler();
    }

    // ============================
    // 🔥 3) DELEGACE KLIKU – STATUS
    // ============================
    document.addEventListener("click", async (e) => {
        if (e.target.classList.contains("change-status")) {

            const tr = e.target.closest("tr");
            const postId = tr.dataset.id;
            const newStatus = e.target.dataset.status;

            try {
                const res = await fetch("app/api/posts.php/status", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        post_id: postId,
                        status_id: newStatus
                    })
                });

                const data = await res.json();

                if (data.success) {
                    alert("Status změněn");
                    location.reload();
                } else {
                    alert(data.error || "Chyba při změně statusu");
                }

            } catch (err) {
                console.error("Chyba při změně statusu:", err);
            }

        }
    });

    // ============================
    // 🔥 4) DELEGACE KLIKU – REZENCENT
    // ============================
    document.addEventListener("click", async (e) => {
        if (e.target.classList.contains("assign-btn")) {

            const tr = e.target.closest("tr");
            const postId = tr.dataset.id;
            const select = tr.querySelector(".assign-reviewer");
            const reviewerId = select.value;

            if (!reviewerId) {
                alert("Vyber recenzenta.");
                return;
            }

            try {
                const res = await fetch("app/api/posts.php/assign", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        post_id: postId,
                        reviewer_id: reviewerId
                    })
                });

                const data = await res.json();

                if (data.success) {
                    const msg = tr.querySelector(".reviewer-msg");
                    msg.textContent = "Recenzent přiřazen.";
                    msg.classList.remove("d-none");
                } else {
                    alert(data.error || "Chyba přiřazení");
                }

            } catch (err) {
                console.error("Chyba přiřazení:", err);
            }

        }
    });

});
