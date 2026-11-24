import 'package:flutter/foundation.dart';

class LanguageController extends ChangeNotifier {
  static final LanguageController instance = LanguageController();

  bool _isSwahili = true;

  bool get isSwahili => _isSwahili;
  String get currentCode => _isSwahili ? 'sw' : 'en';

  void toggleLanguage() {
    _isSwahili = !_isSwahili;
    notifyListeners();
  }

  void setSwahili(bool value) {
    _isSwahili = value;
    notifyListeners();
  }

  String t(String swText, String enText) {
    return _isSwahili ? swText : enText;
  }
}
