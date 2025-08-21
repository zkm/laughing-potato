# Test Documentation

This directory contains comprehensive tests for the Sweetwater Web Programmer Test Project.

## Test Structure

### Files
- `CommentsTest.php` - Tests for Task 1 (comment categorization)
- `ShipDateTest.php` - Tests for Task 2 (ship date parsing)
- `TestSuite.php` - Integration test suite that runs all tests
- `README.md` - This documentation file

## Running Tests

### Run All Tests
```bash
# From the tests directory
php TestSuite.php
```

### Run Individual Test Files
```bash
# Test comment categorization only
php CommentsTest.php

# Test ship date parsing only
php ShipDateTest.php
```

### Run Tests in Docker Container
```bash
# From project root
docker exec php_sweetwater php /var/www/html/../tests/TestSuite.php
```

## Test Coverage

### Task 1 - Comment Categorization Tests
- ✅ **Basic Categorization**: Tests grouping comments into candy, call me, referred, signature, and misc categories
- ✅ **Case Insensitive Matching**: Ensures CANDY, candy, Candy all match correctly  
- ✅ **Edge Cases**: Tests empty comments, multiple keywords, special characters
- ✅ **Category Priority**: Tests which category wins when multiple keywords are present

### Task 2 - Ship Date Parsing Tests
- ✅ **Date Extraction**: Tests parsing "Expected ship date: YYYY-MM-DD" from comments
- ✅ **Case Insensitive**: Tests "EXPECTED SHIP DATE", "expected ship date", etc.
- ✅ **Date Validation**: Ensures invalid dates (13th month, 32nd day) are rejected
- ✅ **Multiple Dates**: Tests handling when comment contains multiple dates
- ✅ **Performance**: Tests with 1000+ records to ensure scalability
- ✅ **SQL Injection Protection**: Verifies safe database updates

### Integration Tests
- ✅ **Database Structure**: Verifies table exists with correct columns
- ✅ **Data Integrity**: Checks test data is properly inserted and updated
- ✅ **Application Endpoints**: Tests actual web pages are accessible
- ✅ **End-to-End Functionality**: Verifies complete workflow works

## Test Data

Tests use temporary tables to avoid affecting production data:
- `sweetwater_test_temp` - Used for comment categorization tests
- `sweetwater_shipdate_test` - Used for ship date parsing tests

All test tables are automatically cleaned up after test completion.

## Expected Results

When all tests pass, you should see:
```
🎉 ALL TESTS PASSED! Your implementation is working correctly.
```

## Common Issues

### Database Connection Errors
- Ensure Docker containers are running: `docker-compose up -d`
- Verify database credentials in `config.php`

### White Screen in Browser
- Check Apache/PHP error logs: `docker logs php_sweetwater`
- Ensure all PHP files have proper opening tags

### Test Failures
- Review error messages for specific assertion failures
- Check that your regex patterns match the expected format
- Verify database updates are working correctly

## Adding New Tests

To add new test cases:

1. **For Comment Tests**: Add to `$testData` array in `CommentsTest.php`
2. **For Ship Date Tests**: Add to `$testData` array in `ShipDateTest.php`
3. **For Integration Tests**: Add new methods to `IntegrationTestSuite` class

Example test case:
```php
['orderid' => 99, 'comments' => 'Your test comment here']
```

## Performance Benchmarks

Current benchmarks (on average development machine):
- Comment categorization: ~0.001s per record
- Ship date parsing: ~0.002s per record
- 1000 record batch update: <1 second

## Security Considerations

Tests verify:
- SQL injection prevention in date parsing
- Proper escaping of user input
- Safe regex pattern matching
- Database transaction integrity
