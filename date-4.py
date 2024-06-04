'''
l1=['red','sweet']
l2=['apple','cherry']
for i in range(2):
    for j in range(2):
        print(l1[i],l2[j])


'''
# patterns
'''
num=int(input('enter A limit :'))
for i in range(num+1):
    for j in range(i+1):
        print(j,end=' ')
    print()

for i in range(num+1):
    for j in range(i+1):
        print('*',end=' ')
    print()

for i in range(1,num+1):
    for j in range(i):
        print(i,end=' ')
    print()

for i in range(1,num+1):
    for j in range(i,num+1):
        print('*',end=' ')
    print()
c=1
for i in range(1,num+1):
    for j in range(i):
        print(c,end=' ')
        c+=1
    print()
'''
'''
s='PYTHON'
for i in range(len(s)):
    for j in range(i+1):
        print(s[j],end=" ")
    print()

'''
'''
start=int(input('Enter a starting number :'))
end=int(input('Enter a ending numbers :'))
for i in range(start,end+1):
    c=0
    for j in range(1,i+1):
        if i%j==0:
            c+=1
    if c==2:
        print(i)
'''
num=int(input('Enter a limit :'))
for i in range(1,num+1):
    for j in range(1,num+1):
        print(i,'*',j,'=',j*i,end='   ')
    print()
