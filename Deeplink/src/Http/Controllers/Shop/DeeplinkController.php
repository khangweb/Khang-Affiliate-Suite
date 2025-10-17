<?php
namespace KhangWeb\Deeplink\Http\Controllers\Shop;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Webkul\Product\Repositories\ProductRepository;
use KhangWeb\Deeplink\Models\ProductUrl;
use KhangWeb\Deeplink\Models\DeeplinkTemplate;
use Illuminate\Support\Str;

class DeeplinkController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function redirect($productId)
    {
        // 1. Truy vấn product cha nếu có
        $product = $this->productRepository->findOrFail($productId);

        $parentId = $product->parent_id ?? $product->id;
        $imageUrl = $product->images->first()?->url ?? asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.png');

        $imageUrl = $product->images->first()->path ?? null;

        if ($imageUrl && !preg_match('/^https?:\/\//', $imageUrl)) {
            $imageUrl = asset('storage/'.$imageUrl);
     
        }


        // 2. Kiểm tra xem có link_aff trong ProductUrl không
        $productUrl = ProductUrl::where('product_id', $parentId)->first();

        if (!$productUrl) {
            return $this->renderError(__('deeplink.shop-view.out-stock'));
        }

        if (!empty($productUrl->link_aff)) {
            return $this->redirectView($productUrl->link_aff , $imageUrl);
        }

        // 3. Tìm DeeplinkTemplate đang active và khớp domain
        // B1: Lấy domain từ source link
            $host = parse_url($productUrl->link_source, PHP_URL_HOST);
            $domain = Str::replaceFirst('www.', '', $host); // amazon.com

        // B2: Tìm DeeplinkTemplate phù hợp
        $template = DeeplinkTemplate::query()
            ->where('status', true)
            ->whereJsonContains('accepted_domains', $domain)
            ->first();


        if (!$template) {
            return $this->renderError(__('deeplink.shop-view.out-stock'));
        }

        // 4. Xử lý tạo deeplink
        $deeplink = $this->buildDeeplink($productUrl->link_source , $template);

        return $this->redirectView($deeplink ,$imageUrl );
    }

    public function buildDeepLink(string $sourceLink, DeeplinkTemplate $template): ?string
    {

        if (!$template->base_url && !$template->query_template) {
            return null;
        }

        $productUrl = $template->should_encode_url ? urlencode($sourceLink) : $sourceLink;

        $query = str_replace('{product_url}', $productUrl, $template->query_template);

        if ($template->apply_directly_to_product_url) {
            $deepLink = $sourceLink;
        } else {
            $deepLink = $template->base_url;
        }

        // Ghép base_url và query
        return $deepLink.$query ;
    }


    protected function redirectView(string $finalLink , $productImage)
    {

        return view('deeplink::shop.redirect', [
            'targetUrl' => $finalLink,
            'imageUrl' => $productImage, // URL ảnh sản phẩm
        ]);
    }

    protected function renderError(string $message)
    {
        return response()->view('deeplink::shop.error', [
            'errorCode' => 400, // hoặc 404, 500 tùy theo ngữ cảnh
            'customMessage' => $message, // Nếu bạn muốn dùng thông báo riêng
        ], 400);
    }
}
