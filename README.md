# Konferenční systém pro správu článků a recenzí  
**Autor:** Jan Chaloupka  
**Email:** 
**Předmět:** KIV/WEB  
**Datum vytvoření:** 22. 11. 2025  

---

## 1. Účel aplikace
Cílem projektu bylo vytvořit webový konferenční systém, který umožňuje správu odborných článků, recenzní řízení, administraci uživatelů a publikaci schválených příspěvků.

Aplikace umožňuje:

- Registraci a přihlášení uživatelů (Autor, Recenzent, Admin, Superadmin)
- Nahrávání článků v PDF a editaci abstraktu
- Přiřazování recenzentů k článkům (admin/superadmin)
- Tvorbu recenzí pomocí CKEditoru + hvězdičkového hodnocení
- Schvalování či zamítnutí recenzí a článků
- Změnu rolí, blokaci a mazání uživatelů přes REST API
- Veřejné zobrazení schválených článků pomocí PDF.js vieweru
- Mazání článků, recenzí i uživatelů
- Animace na homepage (IntersectionObserver, typing efekt)

---

## 2. Použité technologie

### **Frontend**
- HTML5
- Bootstrap 5 — responzivita + komponenty
- Bootstrap Icons
- JavaScript (ES6)
  - třídy pro organizaci logiky
  - `fetch()` komunikace s REST API (AJAX)
  - animace (fade-in, typing)
- CKEditor — WYSIWYG editor pro psaní recenzí

### **Backend**
- PHP 8
- Twig — šablonovací systém
- MVC architektura (Controllers → Models → Views)
- Composer:
  - HTMLPurifier = ochrana proti XSS
- MySQL + PDO
- Prepared statements = ochrana proti SQL injection
- `password_hash()` + `password_verify()` = bezpečné ukládání hesel
- REST API (PHP)
  - změna rolí, blokace, mazání
  - ukládání recenzí
  - správa článků

---

## 3. Adresářová struktura projektu

```text
├── app/
│   ├── api/
│   │   ├── posts.php               # REST API pro články
│   │   ├── reviews.php             # REST API pro recenze
│   │   └── users.php               # REST API pro správu uživatelů
│   │
│   ├── config/
│   │   ├── config.php              # Cesty, definice konstant, getDB()
│   │   └── db.php                  # DB login údaje
│   │
│   ├── controllers/
│   │   ├── ArticlesController.php
│   │   ├── DeletePostController.php
│   │   ├── DeleteReviewController.php
│   │   ├── EditPostController.php
│   │   ├── HomeController.php
│   │   ├── LoginController.php
│   │   ├── LoginErrorController.php
│   │   ├── LogoutController.php
│   │   ├── ManagePostsController.php
│   │   ├── ManageReviewsController.php
│   │   ├── MyPostsController.php
│   │   ├── ProgramController.php
│   │   ├── RegistrationController.php
│   │   ├── ReviewController.php
│   │   ├── ReviewListController.php
│   │   ├── UploadController.php
│   │   └── UserSettingsController.php
│   │
│   ├── models/
│   │   ├── Database.php            # PDO wrapper
│   │   ├── PostModel.php           # Články
│   │   ├── ReviewModel.php         # Recenze
│   │   └── UserModel.php           # Uživatelé
│   │
│   ├── script/
│   │   └── script.js               # JS logika + animace + AJAX (fetch)
│   │
│   ├── style/
│   │   └── style.css
│   │
│   ├── views/twig/
│   │   ├── articles.twig
│   │   ├── base.twig
│   │   ├── editPost.twig
│   │   ├── home.twig
│   │   ├── loginError.twig
│   │   ├── managePosts.twig
│   │   ├── manageReviews.twig
│   │   ├── myPosts.twig
│   │   ├── program.twig
│   │   ├── registration.twig
│   │   ├── review.twig
│   │   ├── reviewList.twig
│   │   ├── upload.twig
│   │   └── userSettings.twig
│   │
│   └── MyApplication.php           # Centrální router + Twig inicializace
│
├── uploads/                        # Nahrané PDF články
├── img/                            # Obrázky
└── vendor/                         # Composer knihovny

```

## 4. Architektura aplikace (MVC)

### **Modely**
- komunikují s databází přes PDO
- obsahují pouze logiku práce s daty  
  - PostModel — CRUD článků, přiřazení recenzentů  
  - ReviewModel — CRUD recenzí  
  - UserModel — login, registrace, role, blokace  

### **Kontrolery**
- ověřují přihlášení a role
- získávají data z modelů
- vybírají a renderují Twig šablony

### **Pohledy (Twig)**
- pouze výpis dat, minimální logika

### **REST API**
- `/app/api/users.php`
  - změna role, blokace, mazání
- `/app/api/posts.php`
  - přiřazení recenzentů, schválení / zamítnutí
- `/app/api/reviews.php`
  - vytvoření / editace recenze
  - publikace / zamítnutí

### **JavaScript**
- Komunikuje s REST API pomocí `fetch()`
- Dynamika:
  - animace článků
  - typing efekt
  - AJAX změna role, blokace, mazání
  - předvyplňování recenzí
  - interaktivní hvězdičky

---

## 5. Výchozí uživatelé

| Jméno | Role | Login | Heslo |
|-------|------|--------|--------|
| SuperAdmin | 1 | super | 12345 |
| Admin | 2 | admin | 12345 |
| Recenzent 0 | 3 | recenzent0 | 12345 |
| Recenzent 1 | 3 | recenzent1 | 12345 |
| Recenzent 2 | 3 | recenzent2 | 12345 |
| Autor 0 | 4 | autor0 | 12345 |
| Autor 1 | 4 | autor1 | 12345 |
| Autor 2 | 4 | autor2 | 12345 |

---

## 6. Práva uživatelů

### **Superadmin (1)**
- může měnit role adminům
- může blokovat kohokoliv
- přiřazuje recenzenty
- schvaluje články i recenze

### **Admin (2)**
- nesmí zasahovat do superadminů
- přiřazuje recenzenty
- schvaluje / zamítá články a recenze

### **Recenzent (3)**
- může napsat nebo upravit recenzi dokud není schválena / zamítnuta

### **Autor (4)**
- nahrává články
- může je upravit a smazat, dokud nejsou schválené nebo zamítnuté

### **Nepřihlášený**
- pouze Domů, Články, Program
- vidí jen schválené články
- může se registrovat

---

## 7. Logika databáze

### **Tabulka `user`**
- uživatelské účty  
- role: 1–4  
- hashed hesla (`password_hash`)  
- blokace, mazání  

### **Tabulka `post`**
- články  
- PDF soubory  
- autor  
- abstrakt  
- stav (1 = v recenzi, 2 = publikováno, 3 = zamítnuto)  

### **Tabulka `post_reviewer`**
- přiřazení 3 recenzentů ke každému článku  

### **Tabulka `review`**
- hodnocení  
- recenzent → článek  
- 3 kritéria (quality/language/originality)  
- CKEditor text (čištěný HTMLPurifierem)  
- schváleno / zamítnuto  

---
