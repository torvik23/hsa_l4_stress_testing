# HSA L4: Stress Testing

## Overview
This is an example project to show how to make stress testing.

## Getting Started
### Preparation
1. Install [Siege](https://github.com/JoeDog/siege) benchmarking tool. If you are using macOS, you can install it via brew
```bash
  brew install siege
```
2. Run the docker containers.
```bash
  docker-compose up -d
```
Be sure to use ```docker-compose down -v``` to cleanup after you're done with tests.
3. Generate test URLs.
> This step can be skipped, you can find a set of prepared urls in the ```./application/resources/urls.txt``` file.

Otherwise, remove ```./application/resources/urls.txt``` file and run next command:
```bash
  siege -q -j -c20 -r2 http://localhost/
```

## Test scenarios without CACHE
1. Concurrent Users = 10, Testing Time = 30 seconds
```bash
siege -c10 -t30S --content-type "application/json" -f application/resources/urls.txt
```
2. Concurrent Users = 25, Testing Time = 30 seconds
```bash
siege -c25 -t30S --content-type "application/json" -f application/resources/urls.txt
```
3. Concurrent Users = 50, Testing Time = 30 seconds
```bash
siege -c50 -t30S --content-type "application/json" -f application/resources/urls.txt
```
4. Concurrent Users = 100, Testing Time = 30 seconds
```bash
siege -c100 -t30S --content-type "application/json" -f application/resources/urls.txt
```

## Test scenarios with CACHE
1. Concurrent Users = 10, Testing Time = 30 seconds
```bash
siege -c10 -t30S --content-type "application/json" -f application/resources/urls.txt
```
2. Concurrent Users = 25, Testing Time = 30 seconds
```bash
siege -c25 -t30S --content-type "application/json" -H 'Cache-Status: 1' -f application/resources/urls.txt
```
3. Concurrent Users = 50, Testing Time = 30 seconds
```bash
siege -c50 -t30S --content-type "application/json" -H 'Cache-Status: 1' -f application/resources/urls.txt
```
4. Concurrent Users = 100, Testing Time = 30 seconds
```bash
siege -c100 -t30S --content-type "application/json" -H 'Cache-Status: 1' -f application/resources/urls.txt
```

### Test Results
#### without CACHE
1. Concurrent Users = 10, Testing Time = 30 seconds
```bash
Transactions:		         183 hits
Availability:		      100.00 %
Elapsed time:		       29.14 secs
Data transferred:	        0.02 MB
Response time:		        1.55 secs
Transaction rate:	        6.28 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		        9.72
Successful transactions:         183
Failed transactions:	           0
Longest transaction:	        2.08
Shortest transaction:	        0.59

```
2. Concurrent Users = 25, Testing Time = 30 seconds
```bash
Transactions:		         172 hits
Availability:		      100.00 %
Elapsed time:		       29.32 secs
Data transferred:	        1.58 MB
Response time:		        4.04 secs
Transaction rate:	        5.87 trans/sec
Throughput:		        0.05 MB/sec
Concurrency:		       23.69
Successful transactions:         172
Failed transactions:	           0
Longest transaction:	        5.88
Shortest transaction:	        0.97
```
3. Concurrent Users = 50, Testing Time = 30 seconds
```bash
Transactions:		         181 hits
Availability:		      100.00 %
Elapsed time:		       29.02 secs
Data transferred:	        1.74 MB
Response time:		        7.04 secs
Transaction rate:	        6.24 trans/sec
Throughput:		        0.06 MB/sec
Concurrency:		       43.89
Successful transactions:         181
Failed transactions:	           0
Longest transaction:	        9.97
Shortest transaction:	        1.43
```
4. Concurrent Users = 100, Testing Time = 30 seconds
```bash
Transactions:		         162 hits
Availability:		      100.00 %
Elapsed time:		       29.35 secs
Data transferred:	        0.04 MB
Response time:		       12.77 secs
Transaction rate:	        5.52 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		       70.47
Successful transactions:         162
Failed transactions:	           0
Longest transaction:	       19.72
Shortest transaction:	        0.71
```
5. After Concurrent Users = 100 performance starts to degrade and the first errors appear on Concurrent Users = 600. Here is the result of Concurrent Users = 1000
```bash
Transactions:		         392 hits
Availability:		       27.68 %
Elapsed time:		       59.36 secs
Data transferred:	        0.09 MB
Response time:		       30.18 secs
Transaction rate:	        6.60 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		      199.31
Successful transactions:         392
Failed transactions:	        1024
Longest transaction:	       58.99
Shortest transaction:	        0.00
```
#### with CACHE
1. Concurrent Users = 10, Testing Time = 30 seconds
```bash
Transactions:		         177 hits
Availability:		      100.00 %
Elapsed time:		       29.50 secs
Data transferred:	        0.02 MB
Response time:		        1.64 secs
Transaction rate:	        6.00 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		        9.84
Successful transactions:         177
Failed transactions:	           0
Longest transaction:	        2.43
Shortest transaction:	        0.59
```
2. Concurrent Users = 25, Testing Time = 30 seconds
```bash
Transactions:		         181 hits
Availability:		      100.00 %
Elapsed time:		       29.78 secs
Data transferred:	        0.81 MB
Response time:		        3.89 secs
Transaction rate:	        6.08 trans/sec
Throughput:		        0.03 MB/sec
Concurrency:		       23.63
Successful transactions:         181
Failed transactions:	           0
Longest transaction:	        5.00
Shortest transaction:	        0.62
```
3. Concurrent Users = 50, Testing Time = 30 seconds
```bash
Transactions:		         182 hits
Availability:		      100.00 %
Elapsed time:		       29.91 secs
Data transferred:	        0.00 MB
Response time:		        7.16 secs
Transaction rate:	        6.08 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		       43.59
Successful transactions:         182
Failed transactions:	           0
Longest transaction:	        8.81
Shortest transaction:	        0.68
```
4. Concurrent Users = 100, Testing Time = 30 seconds
```bash
Transactions:		         183 hits
Availability:		      100.00 %
Elapsed time:		       29.05 secs
Data transferred:	        0.02 MB
Response time:		       11.52 secs
Transaction rate:	        6.30 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		       72.55
Successful transactions:         183
Failed transactions:	           0
Longest transaction:	       16.28
Shortest transaction:	        0.70
```
5. After Concurrent Users = 100 performance starts to degrade and the first errors appear on Concurrent Users = 800. Here is the result of Concurrent Users = 1000
```bash
Transactions:		         144 hits
Availability:		       15.25 %
Elapsed time:		       29.69 secs
Data transferred:	        0.00 MB
Response time:		       14.69 secs
Transaction rate:	        4.85 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		       71.24
Successful transactions:         144
Failed transactions:	         800
Longest transaction:	       29.54
Shortest transaction:	        0.00
```

Project has performance gaps between 600 and more concurrent users. In our case the bottleneck is nginx with only 1 worker process. If we increase amount of workers to 4 with the same amount of concurrent users (1000), we can see following indicators:
```bash
Transactions:		         168 hits
Availability:		      100.00 %
Elapsed time:		       29.38 secs
Data transferred:	        0.00 MB
Response time:		       15.12 secs
Transaction rate:	        5.72 trans/sec
Throughput:		        0.00 MB/sec
Concurrency:		       86.46
Successful transactions:         168
Failed transactions:	           0
Longest transaction:	       28.67
Shortest transaction:	        0.00
```