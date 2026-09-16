<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MembershipCategory;
use App\Models\TrainingProgramme;
use App\Models\TrainingCourse;
use App\Models\TrainingSession;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Leader;
use App\Models\GovernanceBody;
use App\Models\Partner;
use App\Models\PartnershipEnquiry;
use App\Models\News;
use App\Models\Event;
use App\Models\Resource;
use App\Models\ContactMessage;
use App\Models\Complaint;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\Fee;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = MembershipCategory::where('is_active', true)->orderBy('order_number')->get();
        $programmes = TrainingProgramme::where('is_active', true)->orderBy('order_number')->take(4)->get();
        $projects = Project::where('is_featured', true)->take(3)->get();
        $opportunities = Opportunity::where('status', 'published')->orderBy('deadline', 'asc')->take(4)->get();
        $news = News::where('is_published', true)->orderBy('publication_date', 'desc')->take(3)->get();
        $partners = Partner::where('is_featured', true)->orderBy('order_number')->get();
        $upcomingSessions = TrainingSession::with('course.programme')->where('status', 'upcoming')->orderBy('start_date')->take(3)->get();

        return view('public.home', compact(
            'categories',
            'programmes',
            'projects',
            'opportunities',
            'news',
            'partners',
            'upcomingSessions'
        ));
    }

    public function about()
    {
        $foundingLeaders = Leader::where('is_founding_leader', true)->orderBy('order_number')->get();
        $governanceBodies = GovernanceBody::orderBy('order_number')->get();
        $faqs = Faq::where('is_active', true)->orderBy('order_number')->take(6)->get();

        return view('public.about', compact('foundingLeaders', 'governanceBodies', 'faqs'));
    }

    public function leadership()
    {
        $foundingLeaders = Leader::where('is_founding_leader', true)->orderBy('order_number')->get();
        $nationalLeaders = Leader::where('is_founding_leader', false)->where('is_active', true)->orderBy('order_number')->get();
        $governanceBodies = GovernanceBody::with('leaders')->orderBy('order_number')->get();

        return view('public.leadership', compact('foundingLeaders', 'nationalLeaders', 'governanceBodies'));
    }

    public function membershipInfo()
    {
        $categories = MembershipCategory::where('is_active', true)->orderBy('order_number')->get();
        $fees = Fee::with('category')->where('is_active', true)->get();

        return view('public.membership', compact('categories', 'fees'));
    }

    public function programmes(Request $request)
    {
        $programmes = TrainingProgramme::with(['courses.modules'])
            ->where('is_active', true)
            ->orderBy('order_number')
            ->get();

        $upcomingSessions = TrainingSession::with('course.programme')
            ->where('status', 'upcoming')
            ->orderBy('start_date')
            ->get();

        return view('public.programmes', compact('programmes', 'upcomingSessions'));
    }

    public function projects(Request $request)
    {
        $query = Project::with('category');
        
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $projects = $query->latest()->paginate(9);
        $categories = ProjectCategory::all();

        return view('public.projects', compact('projects', 'categories'));
    }

    public function showProject(string $slug)
    {
        $project = Project::with(['category', 'applications'])->where('slug', $slug)->firstOrFail();
        $relatedProjects = Project::where('id', '!=', $project->id)->take(3)->get();

        return view('public.project_show', compact('project', 'relatedProjects'));
    }

    public function opportunities(Request $request)
    {
        $query = Opportunity::with('category')->where('status', 'published');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $opportunities = $query->orderBy('deadline', 'asc')->paginate(9);
        $categories = OpportunityCategory::all();

        return view('public.opportunities', compact('opportunities', 'categories'));
    }

    public function showOpportunity(string $slug)
    {
        $opportunity = Opportunity::with('category')->where('slug', $slug)->firstOrFail();
        $relatedOpportunities = Opportunity::where('id', '!=', $opportunity->id)->where('status', 'published')->take(3)->get();

        return view('public.opportunity_show', compact('opportunity', 'relatedOpportunities'));
    }

    public function partners()
    {
        $partners = Partner::orderBy('order_number')->get();
        return view('public.partners', compact('partners'));
    }

    public function submitPartnerEnquiry(Request $request)
    {
        $validated = $request->validate([
            'organisation_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'collaboration_interests' => 'nullable|string',
            'message' => 'required|string|max:2000',
        ]);

        PartnershipEnquiry::create($validated);

        return back()->with('success', 'Thank you for your interest in partnering with TEVDA. Our Secretariat will review your inquiry and connect with you shortly.');
    }

    public function news(Request $request)
    {
        $query = News::where('is_published', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $news = $query->orderBy('publication_date', 'desc')->paginate(6);
        $events = Event::where('status', 'upcoming')->orderBy('event_date')->take(4)->get();

        return view('public.news', compact('news', 'events'));
    }

    public function showNews(string $slug)
    {
        $article = News::where('slug', $slug)->firstOrFail();
        $recentNews = News::where('id', '!=', $article->id)->where('is_published', true)->latest()->take(3)->get();

        return view('public.news_show', compact('article', 'recentNews'));
    }

    public function resources(Request $request)
    {
        $query = Resource::where('is_published', true)->where('visibility', 'public');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $resources = $query->latest()->paginate(12);

        return view('public.resources', compact('resources'));
    }

    public function downloadResource(string $slug)
    {
        $resource = Resource::where('slug', $slug)->firstOrFail();
        $resource->increment('downloads_count');

        if (file_exists(public_path($resource->file_path))) {
            return response()->download(public_path($resource->file_path));
        }

        return back()->with('info', 'Document file is available for consultation through TEVDA Secretariat offices.');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'reason' => 'required|string',
            'message' => 'required|string|max:3000',
            'consent' => 'accepted',
        ]);

        $validated['consent_given'] = true;
        unset($validated['consent']);

        ContactMessage::create($validated);

        return back()->with('success', 'Your message has been received by the TEVDA Secretariat. We will respond promptly.');
    }

    public function whistleblower()
    {
        return view('public.whistleblower');
    }

    public function submitWhistleblower(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'description' => 'required|string|max:5000',
            'reporter_name' => 'nullable|string|max:255',
            'reporter_phone' => 'nullable|string|max:20',
            'reporter_email' => 'nullable|email|max:255',
            'is_anonymous' => 'nullable|boolean',
            'priority' => 'nullable|string',
            'evidence' => 'nullable|file|mimes:pdf,jpg,png,jpeg,doc,docx|max:10240',
        ]);

        $isAnonymous = $request->boolean('is_anonymous') || empty($validated['reporter_name']);
        $complaintNumber = Complaint::generateComplaintNumber();

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $doc = DocumentService::storePrivate($request->file('evidence'), 'complaints');
            $evidencePath = $doc['file_path'];
        }

        $complaint = Complaint::create([
            'complaint_number' => $complaintNumber,
            'category' => $validated['category'],
            'description' => $validated['description'],
            'evidence_file_path' => $evidencePath,
            'reporter_name' => $isAnonymous ? null : ($validated['reporter_name'] ?? null),
            'reporter_phone' => $isAnonymous ? null : ($validated['reporter_phone'] ?? null),
            'reporter_email' => $isAnonymous ? null : ($validated['reporter_email'] ?? null),
            'is_anonymous' => $isAnonymous,
            'priority' => $validated['priority'] ?? 'normal',
            'status' => 'submitted',
        ]);

        return redirect()->route('whistleblower.track', ['ticket' => $complaintNumber])
            ->with('success', "Report submitted confidentially. Your Reference Ticket ID is {$complaintNumber}. Please keep this number safe to check report progress.");
    }

    public function trackWhistleblower(Request $request)
    {
        $ticket = $request->query('ticket');
        $complaint = null;

        if ($ticket) {
            $complaint = Complaint::where('complaint_number', $ticket)->first();
        }

        return view('public.whistleblower_track', compact('complaint', 'ticket'));
    }

    public function privacy()
    {
        return view('public.legal.privacy');
    }

    public function terms()
    {
        return view('public.legal.terms');
    }

    public function cookies()
    {
        return view('public.legal.cookies');
    }

    public function codeOfConduct()
    {
        return view('public.legal.code_of_conduct');
    }

    public function sitemap()
    {
        $news = News::where('is_published', true)->get();
        $projects = Project::all();
        $opportunities = Opportunity::where('status', 'published')->get();

        return response()->view('public.sitemap', compact('news', 'projects', 'opportunities'))
            ->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /portal\nDisallow: /documents/\nSitemap: " . url('/sitemap.xml');
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
