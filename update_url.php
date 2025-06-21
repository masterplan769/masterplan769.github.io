<?php
// --- Konfiguráció ---
// GitHub repó helyi mappája (pl. a te gépeden)
$repoPath = "C:/Users/Planscaut/masterplan769.github.io/mypage";

// Frissítendő Cloudflare Tunnel URL
$currentUrl = "https://your-current-tunnel-url.trycloudflare.com";

// --- Script ---

// current-url.json elérési út
$jsonFile = $repoPath . "/current-url.json";

// JSON adat előállítása
$jsonData = json_encode(["url" => $currentUrl], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// JSON fájl írása
if (file_put_contents($jsonFile, $jsonData) === false) {
    die("Nem sikerült írni a JSON fájlt: $jsonFile\n");
}

// Ellenőrizzük, hogy a repó mappa létezik-e
if (!is_dir($repoPath)) {
    die("Nem található a repó mappa: $repoPath\n");
}

// Átváltás a repó mappára
chdir($repoPath);

// Git add
exec("git add current-url.json", $outputAdd, $returnAdd);
if ($returnAdd !== 0) {
    die("Hiba a git add során.\n");
}

// Git commit (csak ha van változás)
exec('git diff --cached --quiet', $outputDiff, $returnDiff);
if ($returnDiff !== 0) {
    exec('git commit -m "Automatikus tunnel URL frissítés"', $outputCommit, $returnCommit);
    if ($returnCommit !== 0) {
        die("Hiba a git commit során.\n");
    }

    // Git push
    exec('git push origin main', $outputPush, $returnPush);
    if ($returnPush !== 0) {
        die("Hiba a git push során.\n");
    }

    echo "Tunnel URL frissítve és feltöltve GitHub-ra.\n";
} else {
    echo "Nincs változás, commit nem történt.\n";
}
