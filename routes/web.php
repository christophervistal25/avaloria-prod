<?php

use Inertia\Inertia;
use App\Models\VoteLog;
use App\Models\Download;
use App\Models\GameNews;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\DonateController;
use App\Http\Controllers\Auth\PaypalController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\GameNewsController;
use App\Http\Controllers\Admin\CharacterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WikipediaController;
use App\Http\Controllers\UserDonateGCashController;
use App\Http\Controllers\Admin\RedeemCodeController;
use App\Http\Controllers\Auth\DonatePaypalController;
use App\Http\Controllers\Admin\DonateRankingController;
use App\Http\Controllers\Admin\DonateVoucherController;
use App\Http\Controllers\Admin\RedeemHistoryController;
use App\Http\Controllers\Admin\SetupGCashDonateController;
use App\Http\Controllers\Admin\SetupPaypalDonateController;
use App\Http\Controllers\Auth\HomeController as UserController;
use App\Http\Middleware\EnsureGMLevel;
use App\Http\Controllers\VoteController;
use App\Models\Character;

Route::post('/login', [LoginController::class, 'submitLogin'])->name('login');
Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
});

Route::get('/', function () {
    $news = GameNews::latest()->limit(2)->get();
    return Inertia::render('Home', [
        'news' => $news
    ]);
});

Route::get('download', function () {
    $downloads = Download::orderBy('created_at', 'DESC')->get();
    return Inertia::render('Downloads', [
        'downloads' => $downloads
    ]);
});


Route::get('rankings', function () {
    $topCharacters = Character::orderBy('m_nLevel', 'DESC')->orderBy('TotalPlayTime', 'DESC')->take(3)->get(['m_idPlayer', 'm_szName', 'account', 'TotalPlayTime', 'm_nLevel']);
    
    $topCharacterIds = $topCharacters->pluck('m_idPlayer')->toArray();
    
    $characters = Character::orderBy('m_nLevel', 'DESC')
        ->orderBy('TotalPlayTime', 'DESC')
        ->whereNotIn('m_idPlayer', $topCharacterIds)
        ->paginate(20, ['m_idPlayer', 'm_szName', 'account', 'TotalPlayTime', 'm_nLevel']);
    
    return Inertia::render('Rankings', [
        'topCharacters' => $topCharacters,
        'characters' => $characters,
    ]);
});

Route::get('login', function () {
    return Inertia::render('Login', [
    ]);
})->middleware('guest');

Route::get('register', function () {
    return Inertia::render('Register', [
    ]);
});

Route::post('register', [RegisterController::class, 'submitRegisterForm'])->name('register');

Route::get('donates', [DonateController::class, 'index'])->name('donates');

Route::get('wiki', function () {
    $categories = App\Models\Wikipedia::with(['items'])
        ->where('is_published', true)
        ->latest()
        ->get();

    return Inertia::render('Wikipedia/Category', [
        'categories' => $categories
    ]);
})->name('wiki.index');


Route::get('news', function () {
    $news = GameNews::latest()->get();
    return Inertia::render('News', [
        'news' => $news
    ]);
})->name('news.index');

Route::get('news/{news}', function (GameNews $news) {
    return Inertia::render('NewsShow', [
        'newsItem' => $news
    ]);
})->name('news.show');

Route::get('wiki/{wikipedia}', function (App\Models\Wikipedia $wikipedia) {
    return Inertia::render('Wikipedia/Show', [
        'wikipedia' => $wikipedia->load('items')
    ]);
})->name('wiki.show');


Route::get('flyff-events', [EventController::class, 'getEvents']);

Route::group(['prefix' => 'user-account'], function () {
    Route::get('dashboard', [UserController::class, 'index'])->name('me.dashboard');
    Route::post('new-password', [UserController::class, 'newPassword'])->name('me.new-password');
    Route::post('in-game-account', [UserController::class, 'createInGameAccount']);
    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::post('redeem-code',  [UserController::class, 'redeemCode']);
});

Route::post('paypal', [PaypalController::class, 'paypal'])->name('paypal');
Route::get('paypal-checkout/success', [PaypalController::class, 'success'])->name('paypal-checkout.success');
Route::get('paypal-checkout/cancel', [PaypalController::class, 'cancel'])->name('paypal-checkout.cancel');

Route::get('gcash-donate-on-process', [UserDonateGCashController::class, 'OnProcess'])->name('gcash-donate-on-process');
Route::post('gcash-checkout', [UserDonateGCashController::class, 'GCashCheckoutVerify'])->name('gcash-checkout-verify');
Route::get('gcash-checkout/{id}', [UserDonateGCashController::class, 'GcashCheckOut'])->name('gcash-checkout');
Route::post('donate-gcash', [UserDonateGCashController::class, 'VerifyRequest']);

Route::post('donate-paypal', DonatePaypalController::class);



Route::group(['prefix' => 'administrator-panel', 'middleware' => ['auth', EnsureGMLevel::class]], function () {
    Route::redirect('/', '/administrator-panel/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('gcash-donates/send-cash', [DashboardController::class, 'sendCashWithGCash'])->name('admin.gcash.donate');
    Route::post('gcash-donates/delete-cash', [DashboardController::class, 'deleteCashWithGCash'])->name('admin.gcash.delete');


    Route::get('accounts', [AccountController::class, 'index'])->name('admin.accounts.index');
    Route::get('account/{account}', [AccountController::class, 'show'])->name('admin.accounts.show');
    Route::post('account/{account}/points', [AccountController::class, 'modifyPoints'])->name('admin.account.modify.points');
    Route::post('account/{account}/credentials', [AccountController::class, 'updateAccountCredentials'])->name('admin.account.update');
    Route::post('account/{account}/ban', [AccountController::class, 'banAccount'])->name('admin.account.ban');
    Route::get('account/{account}/characters', [AccountController::class, 'characters'])->name('admin.account.characters');

    Route::get('characters', [CharacterController::class, 'index'])->name('admin.characters.index');

    Route::get('news', [GameNewsController::class, 'index']);
    Route::get('create-news', [GameNewsController::class, 'create']);
    Route::post('create-news', [GameNewsController::class, 'store']);
    Route::Get('edit-news/{id}', [GameNewsController::class, 'edit']);
    Route::get('edit-news/{id}', [GameNewsController::class, 'edit']);
    Route::post('edit-news/{id}', [GameNewsController::class, 'update']);
    Route::delete('delete-news/{id}', [GameNewsController::class, 'destroy']);
    Route::delete('delete-news/{id}', [GameNewsController::class, 'destroy']);

    Route::get('gcash-donate-setup', [SetupGCashDonateController::class, 'index']);
    Route::post('gcash-donate-setup', [SetupGCashDonateController::class, 'store']);
    Route::put('gcash-donate-setup/{id}', [SetupGCashDonateController::class, 'update']);
    Route::resource('paypal-donate-setup', SetupPaypalDonateController::class);

    Route::get('donate-rankings', [DonateRankingController::class, 'index']);
    Route::get('donate-rankings/fetch', [DonateRankingController::class, 'fetchDonationRanking']);
    Route::get('donate-vouchers', function () {
        return Inertia::render('Admin/DonateVoucherDistributed');
    });
    Route::get('donate-vouchers-per-character', [DonateVoucherController::class, 'index']);

    
    Route::get('downloads', [DownloadController::class, 'index'])->name('admin.downloads.index');
    Route::get('downloads/create', [DownloadController::class, 'create']);
    Route::post('downloads/create', [DownloadController::class, 'store']);
    Route::get('downloads/edit/{id}', [DownloadController::class, 'edit']);
    Route::post('downloads/edit/{id}', [DownloadController::class, 'update']);
    Route::post('downloads/delete/{id}', [DownloadController::class, 'destroy']);

    Route::post('/redeem-codes/bulk-delete', [RedeemCodeController::class, 'bulkDelete'])->name('admin.redeem-codes.bulk-delete');
    Route::resource('redeem-codes', RedeemCodeController::class)->names('admin.redeem-codes');
    Route::get('redeem-history', [RedeemHistoryController::class, 'index'])->name('admin.redeem-history.index');
    Route::resource('wikipedia', WikipediaController::class)->names('admin.wikipedia');

    Route::get('/vote-logs', function () {
        $voteLogs = VoteLog::latest()->get();
        return Inertia::render('Admin/VoteLogs', [
            'voteLogs' => $voteLogs,
        ]);
    })->name('vote.logs');

    Route::post('logout', function () {
        auth()->logout();
        return redirect()->route('login');
    });
});

// Route::post('review-request', [VoteController::class, 'processVote']);

Route::post('/vote/initiate', [VoteController::class, 'initiateVote']);
Route::get('/vote/status', [VoteController::class, 'checkVoteStatus']);
Route::post('/vote', [VoteController::class, 'processVote']); 


Route::post('/me/logout', function () {
    auth()->logout();

    return response()->json(['success' => true]);
});