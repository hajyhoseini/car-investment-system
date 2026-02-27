<?php
// app/Http/Controllers/ReceivableController.php

namespace App\Http\Controllers;

use App\Models\Receivable;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReceivableController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * نمایش لیست مطالبات
     */
    public function index(Request $request)
    {
        $query = Receivable::with(['person', 'creator'])
            ->latest('receivable_date');

        // فیلتر بر اساس شخص
        if ($request->filled('person_id')) {
            $query->where('person_id', $request->person_id);
        }

        // فیلتر بر اساس نوع ارز
        if ($request->filled('currency_type')) {
            $query->where('currency_type', $request->currency_type);
        }

        // فیلتر بر اساس وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فیلتر بر اساس بازه زمانی
        if ($request->filled('start_date')) {
            $query->whereDate('receivable_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('receivable_date', '<=', $request->end_date);
        }

        $receivables = $query->paginate(20);
        
        // آمار
        $totalAmount = $receivables->sum('amount');
        $totalRemaining = $receivables->sum('remaining_amount');
        $totalPaid = $receivables->sum('paid_amount');
        $overdueCount = $receivables->where('status', 'overdue')->count();

        return view('receivables.index', compact(
            'receivables',
            'totalAmount',
            'totalRemaining',
            'totalPaid',
            'overdueCount'
        ));
    }

    /**
     * فرم ایجاد مطالبه جدید
     */
    public function create()
    {
        $people = Person::orderBy('full_name')->get();
        
        $currencyTypes = [
            'cash' => 'نقد',
            'check' => 'چک',
            'gold' => 'طلا',
            'dollar' => 'دلار',
            'other' => 'سایر',
        ];

        return view('receivables.create', compact('people', 'currencyTypes'));
    }

    /**
     * ذخیره مطالبه جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'currency_type' => 'required|in:cash,check,gold,dollar,other',
            'currency_details' => 'nullable|array',
            'receivable_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:receivable_date',
            'person_id' => 'nullable|exists:people,id',
            'status' => 'required|in:pending,partially_paid,paid,overdue',
            'paid_amount' => 'nullable|numeric|min:0|max:' . $request->amount,
            'attachments' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        // تنظیم مقدار پیش‌فرض برای paid_amount
        $validated['paid_amount'] = $validated['paid_amount'] ?? 0;
        $validated['remaining_amount'] = $validated['amount'] - $validated['paid_amount'];

        // آپلود فایل ضمیمه
        if ($request->hasFile('attachments')) {
            $path = $request->file('attachments')->store('receivables', 'public');
            $validated['attachments'] = $path;
        }

        $validated['created_by'] = auth()->id();

        Receivable::create($validated);

        return redirect()->route('receivables.index')
            ->with('success', 'مطالبه با موفقیت ثبت شد.');
    }

    /**
     * نمایش جزئیات مطالبه
     */
    public function show(Receivable $receivable)
    {
        $receivable->load(['person', 'creator']);
        return view('receivables.show', compact('receivable'));
    }

    /**
     * فرم ویرایش مطالبه
     */
    public function edit(Receivable $receivable)
    {
        $people = Person::orderBy('full_name')->get();
        
        $currencyTypes = [
            'cash' => 'نقد',
            'check' => 'چک',
            'gold' => 'طلا',
            'dollar' => 'دلار',
            'other' => 'سایر',
        ];

        return view('receivables.edit', compact('receivable', 'people', 'currencyTypes'));
    }

    /**
     * بروزرسانی مطالبه
     */
    public function update(Request $request, Receivable $receivable)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'currency_type' => 'required|in:cash,check,gold,dollar,other',
            'currency_details' => 'nullable|array',
            'receivable_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:receivable_date',
            'person_id' => 'nullable|exists:people,id',
            'status' => 'required|in:pending,partially_paid,paid,overdue',
            'paid_amount' => 'nullable|numeric|min:0|max:' . $request->amount,
            'attachments' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        $validated['paid_amount'] = $validated['paid_amount'] ?? 0;
        $validated['remaining_amount'] = $validated['amount'] - $validated['paid_amount'];

        // آپلود فایل جدید
        if ($request->hasFile('attachments')) {
            // حذف فایل قبلی
            if ($receivable->attachments) {
                Storage::disk('public')->delete($receivable->attachments);
            }
            $path = $request->file('attachments')->store('receivables', 'public');
            $validated['attachments'] = $path;
        }

        $receivable->update($validated);

        return redirect()->route('receivables.index')
            ->with('success', 'مطالبه با موفقیت بروزرسانی شد.');
    }

    /**
     * حذف مطالبه
     */
    public function destroy(Receivable $receivable)
    {
        if ($receivable->attachments) {
            Storage::disk('public')->delete($receivable->attachments);
        }
        
        $receivable->delete();

        return redirect()->route('receivables.index')
            ->with('success', 'مطالبه با موفقیت حذف شد.');
    }

    /**
     * ثبت پرداخت جدید برای مطالبه
     */
    public function addPayment(Request $request, Receivable $receivable)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:1000|max:' . $receivable->remaining_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'payment_notes' => 'nullable|string',
        ]);

        $newPaidAmount = $receivable->paid_amount + $request->payment_amount;
        
        $receivable->update([
            'paid_amount' => $newPaidAmount,
            'remaining_amount' => $receivable->amount - $newPaidAmount,
            'status' => $newPaidAmount >= $receivable->amount ? 'paid' : 'partially_paid',
        ]);

        // اینجا می‌تونی یه جدول جدا برای تاریخچه پرداخت‌ها ایجاد کنی

        return redirect()->route('receivables.show', $receivable)
            ->with('success', 'پرداخت با موفقیت ثبت شد.');
    }
}