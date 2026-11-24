import 'package:flutter_test/flutter_test.dart';
import 'package:tevdaapp/main.dart';

void main() {
  testWidgets('TEVDA Mobile App renders 5 primary destinations smoke test', (WidgetTester tester) async {
    // Build our app and trigger a frame.
    await tester.pumpWidget(const TevdaApp());
    await tester.pumpAndSettle();

    // Verify 5 primary destinations exist in bottom navigation bar
    expect(find.text('Mwanzo'), findsOneWidget);
    expect(find.text('Kitambulisho'), findsWidgets);
    expect(find.text('Chuo'), findsOneWidget);
    expect(find.text('Gundua'), findsOneWidget);
    expect(find.text('Wasifu'), findsOneWidget);

    // Verify home header branding exists
    expect(find.text('TEVDA'), findsWidgets);
    expect(find.text('SMART DRIVERS • SMART MOBILITY'), findsOneWidget);
  });
}
