import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'constants.dart';

class ApiResponse<T> {
  final bool success;
  final String message;
  final T? data;
  final dynamic errors;
  final bool isOfflineFallback;

  ApiResponse({
    required this.success,
    required this.message,
    this.data,
    this.errors,
    this.isOfflineFallback = false,
  });
}

class ApiService {
  static final ApiService instance = ApiService();

  String _token = '';
  Map<String, dynamic>? currentUser;
  Map<String, dynamic>? currentMember;
  Map<String, dynamic>? routingState;

  bool get isAuthenticated => _token.isNotEmpty;
  String get token => _token;

  void setToken(String token) {
    _token = token;
  }

  void clearSession() {
    _token = '';
    currentUser = null;
    currentMember = null;
    routingState = null;
  }

  Map<String, String> get _headers => {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        if (_token.isNotEmpty) 'Authorization': 'Bearer $_token',
      };

  // Base URL: in development points to local Laravel server
  String get baseUrl => AppConstants.apiBaseUrl;

  Future<ApiResponse<T>> _get<T>(String endpoint, {T Function(dynamic)? transform}) async {
    final url = Uri.parse('$baseUrl$endpoint');
    try {
      final res = await http.get(url, headers: _headers).timeout(const Duration(seconds: 8));
      final json = jsonDecode(res.body);
      if (res.statusCode >= 200 && res.statusCode < 300 && json['success'] == true) {
        return ApiResponse(
          success: true,
          message: json['message'] ?? 'Success',
          data: transform != null ? transform(json['data']) : json['data'] as T?,
        );
      }
      return ApiResponse(
        success: false,
        message: json['message'] ?? 'Hitilafu ya kupakia taarifa (${res.statusCode}).',
        errors: json['errors'],
      );
    } catch (e) {
      debugPrint('API GET $endpoint Error: $e');
      return ApiResponse(
        success: false,
        message: 'Haikuweza kuunganishwa na seva ya TEVDA. Hakikisha mtandao uko hewani.',
        isOfflineFallback: true,
      );
    }
  }

  Future<ApiResponse<T>> _post<T>(String endpoint, Map<String, dynamic> body, {T Function(dynamic)? transform}) async {
    final url = Uri.parse('$baseUrl$endpoint');
    try {
      final res = await http
          .post(url, headers: _headers, body: jsonEncode(body))
          .timeout(const Duration(seconds: 10));
      final json = jsonDecode(res.body);
      if (res.statusCode >= 200 && res.statusCode < 300 && json['success'] == true) {
        return ApiResponse(
          success: true,
          message: json['message'] ?? 'Success',
          data: transform != null ? transform(json['data']) : json['data'] as T?,
        );
      }
      return ApiResponse(
        success: false,
        message: json['message'] ?? 'Ombi limekataliwa (${res.statusCode}).',
        errors: json['errors'],
      );
    } catch (e) {
      debugPrint('API POST $endpoint Error: $e');
      return ApiResponse(
        success: false,
        message: 'Haikuweza kuwasilisha ombi. Seva haipatikani kwa sasa.',
        isOfflineFallback: true,
      );
    }
  }

  // ==========================================================================
  // CONFIG & GEOGRAPHY
  // ==========================================================================
  Future<ApiResponse<dynamic>> getFees() => _get('/config/fees');
  Future<ApiResponse<dynamic>> getCategories() => _get('/config/categories');
  Future<ApiResponse<dynamic>> getRegions() => _get('/config/regions');
  Future<ApiResponse<dynamic>> getDistricts(int regionId) => _get('/config/districts/$regionId');
  Future<ApiResponse<dynamic>> getWards(int districtId) => _get('/config/wards/$districtId');

  // ==========================================================================
  // AUTHENTICATION & SESSIONS
  // ==========================================================================
  Future<ApiResponse<dynamic>> login(String login, String password) async {
    final res = await _post('/auth/login', {'login': login, 'password': password});
    if (res.success && res.data != null && res.data is Map) {
      final map = res.data as Map<String, dynamic>;
      if (map['token'] != null) {
        setToken(map['token']);
      }
      currentUser = map['user'];
      currentMember = map['member'];
      routingState = map['routing'];
    }
    return res;
  }

  Future<ApiResponse<dynamic>> registerAccount({
    required String name,
    required String phone,
    String? email,
    required String password,
    required String passwordConfirmation,
    bool termsAccepted = true,
  }) async {
    final res = await _post('/auth/register-account', {
      'name': name,
      'phone': phone,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'terms_accepted': termsAccepted,
    });
    if (res.success && res.data != null && res.data is Map) {
      final map = res.data as Map<String, dynamic>;
      if (map['token'] != null) {
        setToken(map['token']);
      }
      currentUser = map['user'];
      currentMember = null;
      routingState = map['routing'];
    }
    return res;
  }

  Future<ApiResponse<dynamic>> sendOtp(String phone) =>
      _post('/auth/send-otp', {'phone': phone});

  Future<ApiResponse<dynamic>> verifyOtp(String phone, String otp) =>
      _post('/auth/verify-otp', {'phone': phone, 'otp': otp});

  Future<ApiResponse<dynamic>> forgotPassword(String login) =>
      _post('/auth/forgot-password', {'login': login});

  Future<ApiResponse<dynamic>> resetPassword({
    required String login,
    required String otp,
    required String password,
    required String passwordConfirmation,
  }) =>
      _post('/auth/reset-password', {
        'login': login,
        'otp': otp,
        'password': password,
        'password_confirmation': passwordConfirmation,
      });

  Future<ApiResponse<dynamic>> getMe() async {
    final res = await _get('/auth/me');
    if (res.success && res.data != null && res.data is Map) {
      final map = res.data as Map<String, dynamic>;
      currentUser = map['user'];
      currentMember = map['member'];
      routingState = map['routing'];
    }
    return res;
  }

  Future<ApiResponse<dynamic>> logout() async {
    final res = await _post('/auth/logout', {});
    clearSession();
    return res;
  }

  // ==========================================================================
  // ONBOARDING & APPLICATION WORKFLOWS
  // ==========================================================================
  Future<ApiResponse<dynamic>> getApplicationStatus() =>
      _get('/member/application/status');

  Future<ApiResponse<dynamic>> getApplicationDraft() =>
      _get('/member/application/draft');

  Future<ApiResponse<dynamic>> saveApplicationDraft(Map<String, dynamic> data) =>
      _post('/member/application/save-draft', data);

  Future<ApiResponse<dynamic>> submitApplication(Map<String, dynamic> data) =>
      _post('/member/application/submit', data);

  Future<ApiResponse<dynamic>> uploadDocument({
    required String documentType,
    required String base64Data,
    required String fileName,
  }) =>
      _post('/member/upload-document', {
        'document_type': documentType,
        'file_base64': base64Data,
        'file_name': fileName,
      });

  Future<ApiResponse<dynamic>> getDocuments() => _get('/member/documents');

  Future<ApiResponse<dynamic>> getProfile() => _get('/member/profile');
  Future<ApiResponse<dynamic>> getSmartId() => _get('/member/smart-id');
  Future<ApiResponse<dynamic>> getCertificate() => _get('/member/certificate');

  // ==========================================================================
  // VEHICLES
  // ==========================================================================
  Future<ApiResponse<dynamic>> getVehicles() => _get('/vehicles');
  Future<ApiResponse<dynamic>> addVehicle(Map<String, dynamic> vehicle) =>
      _post('/vehicles', vehicle);

  // ==========================================================================
  // PAYMENTS & CONTROL NUMBERS
  // ==========================================================================
  Future<ApiResponse<dynamic>> getPaymentHistory() => _get('/payments/history');
  Future<ApiResponse<dynamic>> generateControlNumber(String purpose, double amount) =>
      _post('/payments/generate-control-number', {'purpose': purpose, 'amount': amount});
  Future<ApiResponse<dynamic>> submitPaymentReference(
          String controlNo, String ref, String channel, double amount) =>
      _post('/payments/submit-reference', {
        'control_number': controlNo,
        'transaction_reference': ref,
        'payment_method': channel,
        'amount': amount,
      });

  // ==========================================================================
  // ACADEMY LMS
  // ==========================================================================
  Future<ApiResponse<dynamic>> getCourses() => _get('/academy/courses');
  Future<ApiResponse<dynamic>> getCourse(int id) => _get('/academy/courses/$id');
  Future<ApiResponse<dynamic>> enrollCourse(int courseId) =>
      _post('/academy/enroll', {'course_id': courseId});
  Future<ApiResponse<dynamic>> getMyCourseProgress() => _get('/academy/my-progress');
  Future<ApiResponse<dynamic>> submitAssessment(int courseId, double score) =>
      _post('/academy/submit-assessment', {
        'course_id': courseId,
        'score_percentage': score,
      });

  // ==========================================================================
  // OPPORTUNITIES
  // ==========================================================================
  Future<ApiResponse<dynamic>> getOpportunities({int? categoryId, String? search}) {
    String q = '';
    if (categoryId != null) q += '?category_id=$categoryId';
    if (search != null && search.isNotEmpty) {
      q += '${q.isEmpty ? '?' : '&'}search=${Uri.encodeComponent(search)}';
    }
    return _get('/opportunities$q');
  }

  Future<ApiResponse<dynamic>> applyOpportunity(int id, String notes) =>
      _post('/opportunities/$id/apply', {'notes': notes});
  Future<ApiResponse<dynamic>> getMyApplications() => _get('/opportunities/my/applications');

  // ==========================================================================
  // COMPLAINTS & WHISTLEBLOWER
  // ==========================================================================
  Future<ApiResponse<dynamic>> submitComplaint({
    required String category,
    required String description,
    required bool isAnonymous,
    String? location,
    String? reporterName,
    String? reporterPhone,
  }) =>
      _post('/complaints', {
        'category': category,
        'description': description,
        'is_anonymous': isAnonymous,
        'location': location,
        'reporter_name': reporterName,
        'reporter_phone': reporterPhone,
      });

  Future<ApiResponse<dynamic>> trackComplaint(String ticketNo) =>
      _get('/complaints/track/$ticketNo');
  Future<ApiResponse<dynamic>> getMyComplaints() => _get('/complaints/my');

  // ==========================================================================
  // NOTIFICATIONS
  // ==========================================================================
  Future<ApiResponse<dynamic>> getNotifications() => _get('/notifications');
  Future<ApiResponse<dynamic>> markNotificationRead(String id) {
    final url = Uri.parse('$baseUrl/notifications/$id/read');
    return http
        .put(url, headers: _headers)
        .then((res) {
          final json = jsonDecode(res.body);
          return ApiResponse(success: json['success'] == true, message: json['message'] ?? 'OK');
        })
        .catchError((e) => ApiResponse(success: false, message: 'Network error'));
  }
}
