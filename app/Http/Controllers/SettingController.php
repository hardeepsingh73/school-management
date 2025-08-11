<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;

class SettingController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view settings', only: ['index']),
            new Middleware('permission:create settings', only: ['create', 'store']),
            new Middleware('permission:edit settings', only: ['edit', 'update']),
            new Middleware('permission:delete settings', only: ['destroy']),
        ];
    }
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        $query = $this->searchService->search(Setting::query(), ['key', 'group' => '='], $request);

        $groups = Setting::distinct('group')->pluck('group');
        $settings = $query->orderBy('group')->orderBy('key')->paginate(10);
        return view('settings.index', compact('settings', 'groups'));
    }

    public function create()
    {
        $groups = Setting::distinct('group')->pluck('group');
        return view('settings.form', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:191|unique:settings,key',
            'group' => 'required|string|max:50',
            'type' => 'required|in:string,text,boolean,integer,float,array,json,image',
            'description' => 'nullable|string|max:255',
        ]);

        // Handle image upload if type is image
        if ($request->type === 'image') {
            $validated['value'] = $this->handleImageUpload($request);
        } else {
            $validated['value'] = $request->value;
        }

        Setting::create($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Setting created successfully.');
    }

    public function edit(Setting $setting)
    {
        $groups = Setting::distinct('group')->pluck('group');
        return view('settings.form', compact('setting', 'groups'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:191|unique:settings,key,' . $setting->id,
            'group' => 'required|string|max:50',
            'type' => 'required|in:string,text,boolean,integer,float,array,json,image',
            'description' => 'nullable|string|max:255',
        ]);

        if ($request->type === 'image') {
            // Handle image removal first
            if ($request->has('remove_image') && $setting->value) {
                Storage::disk('public')->delete($setting->value);
                $validated['value'] = null;
            } else {
                // Handle image upload (fallback to current if no new file)
                $validated['value'] = $this->handleImageUpload($request, $setting, 'value');
            }
        } else {
            // If previously image, delete old image
            if ($setting->type === 'image' && $setting->value) {
                Storage::disk('public')->delete($setting->value);
            }
            $validated['value'] = $request->value;
        }

        $setting->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    public function bulkedit()
    {
        $groups = Setting::select('group')->distinct()->orderBy('group', 'asc')->pluck('group');
        $settings = Setting::orderBy('group')->get()->groupBy('group');
        return view('settings.bulk', compact('groups', 'settings'));
    }

    public function bulkupdate(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                continue;
            }

            if ($setting->type === 'image') {
                // Handle image upload
                $fileField = "settings.$key";
                $setting->value = $this->handleImageUpload($request, $setting, $fileField);
                $setting->save();
                continue;
            }

            // Handle non-image settings
            $setting->value = $value;
            $setting->save();
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function destroy(Setting $setting)
    {
        // Delete associated image if exists
        if ($setting->type === 'image' && $setting->value) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->delete();
        return response()->json(['status' => true, 'message' => 'Setting deleted successfully']);
    }

    /**
     * Handle image upload for settings
     */
    protected function handleImageUpload(Request $request, Setting $setting = null, $field = 'value')
    {
        if ($request->hasFile($field)) {
            // Delete old image if exists
            if ($setting && $setting->value) {
                Storage::disk('public')->delete($setting->value);
            }

            // Store and return new image path
            return $request->file($field)->store('settings', 'public');
        }

        // No new file uploaded; return current value
        return $setting->value ?? null;
    }
}
