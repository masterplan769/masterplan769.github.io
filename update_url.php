<?php
// --- Konfiguráció ---
// GitHub repó helyi mappája (pl. a te gépeden)
$repoPath = "$repoPath = "C:/Users/Planscaut/masterplan769.github.io/mypage";";

// Frissítendő Cloudflare Tunnel URL
$currentUrl = "https://your-current-tunnel-url.trycloudflare.com";

// --- Script ---

// current-url.json elérési út
$jsonFile = $repoPath . "/current-url.json";

// JSON adat előállítása
$jsonData = json_encode(["url" => $currentUrl], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// JSON fájl írása
file_put_contents($jsonFile, $jsonData);

// Git műveletek
chdir($repoPath);

// Git add
exec("git add current-url.json", $outputAdd, $returnAdd);
if ($returnAdd !== 0) {
    echo "Hiba a git add során.\n";
    exit(1);
}

// Git commit (csak ha van változás)
exec('git diff --cached --quiet', $outputDiff, $returnDiff);
if ($returnDiff !== 0) {
    exec('git commit -m "Automatikus tunnel URL frissítés"', $outputCommit, $returnCommit);
    if ($returnCommit !== 0) {
        echo "Hiba a git commit során.\n";
        exit(1);
    }

    // Git push
    exec('git push origin main', $outputPush, $returnPush);
    if ($returnPush !== 0) {
        echo "Hiba a git push során.\n";
        exit(1);
    }

    echo "Tunnel URL frissítve és feltöltve GitHub-ra.\n";
} else {
    echo "Nincs változás, commit nem történt.\n";
}
